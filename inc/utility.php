<?php
include_once("database.php");
include_once("session.php");

abstract class Operacion
{
    const CambioUsuario = "Cambio Usuario";
    const Registro = "Registro";
    const Acceso = "Acceso";
    const Backup = "Backup";
    const Notificacion = "Notificacion";
    const Error = "Error";
    const Backend = "Backend";
    const Seguridad = "Seguridad";

    static function getOperazioni()
    {
        $classe = new ReflectionClass(__CLASS__);
        return $classe->getConstants();
    }
}

abstract class Tipologia
{
    const Rainweb = "rainweb";
    const DentalPro = "dentalpro";


    static function getOperazioni()
    {
        $classe = new ReflectionClass(__CLASS__);
        return $classe->getConstants();
    }
}

abstract class Rol
{
    const Rainweb = ['' => 1, 'usuario' => 2, 'manager' => 3,  'administrador' => 4];
    const DentalPro = ['' => 1, 'usuario' => 2,  'manager' => 3, 'administrador' => 4];

    static function getOperazioni()
    {
        $classe = new ReflectionClass(__CLASS__);
        return $classe->getConstants();
    }
}

function limpiarTexto($texto)
{
    $textoLimpio = trim(addslashes(htmlentities(($texto))));
    return $textoLimpio;
}

// envia un mensaje desde el control con la respuesta
// hay 3 categorias warning, error y success
// se usa die() par amatar el proceso en curso ya que se produjo un warning o 
// un error o la funcion termino satisfactoriamente
function response($status, $mensaje, $datos = null)
{
    $response['status'] = $status;
    $response['mensaje'] = $mensaje;
    $response['datos'] = $datos;

    echo json_encode($response);
    exit; // sirve para matar el proceso en curso
}

function setLog($operacion, $mensaje)
{
    global $dbDentalPro;
    global $username_session;
    $mensajeLimpio = limpiarTexto($mensaje);
    $operacion = ucfirst(strtolower($operacion));
    if ($username_session == "")
        $sql = "INSERT INTO log(operacion, descripcion, created_by) VALUES('$operacion','$mensajeLimpio','sistema')";
    else
        $sql = "INSERT INTO log(operacion, descripcion, created_by) VALUES('{$operacion}','{$mensajeLimpio}','{$username_session}')";
    mysqli_query($dbDentalPro, $sql);
}

function GetIP()
{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'IP SCONOSCIUTO';
    return $ipaddress;
}

function getToken($num = 64)
{
    $token = bin2hex(openssl_random_pseudo_bytes($num));
    return $token;
}

function stampa($dato)
{
    echo '<pre>';
    print_r($dato);
    echo '</pre>';
}


// funcion que restringe el acceso a las paginas por rol y por tipologia
// EJENPLO: permiso(array('rainweb' => '','dentalpro' => 'manager'));
// solo los usuarios de rainweb de cualquier tipologia y los usuarios de dentalpro con tipologia manager y superior
// (administrador y manager) pueden acceder a la pagina los demas seran redireccionados
function permiso($arrayTipologiaPermitida, $urlPermisoNegado = 'index.php', $idConsultorio = false)
{
    global $dbDentalPro;
    global $username_session;
    global $tipologia_session;
    global $rol_session;

    // si el db no esta disponible lo mando al index
    if (!$dbDentalPro) {
        echo "<script>alert('Hubo un problema con el servidor... Intentalo mas tarde');</script>";
        header("Location: index.php");
        exit;
    }

    // si la session no es valida lo mando al index (evita el acceso a quien no esta logado)
    if (!isLogado()) {
        SetLog(Operacion::Seguridad, 'se intento acceder de forma invalida con una session no iniciada [' . GetIP() . ']');
        echo "<script>alert('no tienes permiso para acceder a esta pagina');</script>";
        header("Location: index.php");
        exit;
    }

    // si se llega hasta aqui, la session se inicio correctamente

    // si la tipologia de la session no esta en el permiso lo mando al dashboard
    // tambien redirecciono en el caso que no tenga una tipologia y rol en el bd
    if (!array_key_exists($tipologia_session, $arrayTipologiaPermitida)) {
        SetLog(Operacion::Seguridad, "$username_session intento acceder con una tipologia invalida  [" . GetIP() . "]");
        echo "<script>alert('no tienes permiso para acceder a esta pagina');</script>";
        header("Location: $urlPermisoNegado");
        exit;
    }


    $idRolPermiso = 0;
    switch ($tipologia_session) {
        case Tipologia::Rainweb:
            // obtengo el valor del rol del permiso
            $idRolSession = Rol::Rainweb[$rol_session];
            $rolPermiso = $arrayTipologiaPermitida[Tipologia::Rainweb];
            $idRolPermiso = Rol::Rainweb[$rolPermiso];
            break;

        case Tipologia::DentalPro:
            // obtengo el valor del rol del permiso
            $idRolSession = Rol::DentalPro[$rol_session];
            $rolPermiso = $arrayTipologiaPermitida[Tipologia::DentalPro];
            $idRolPermiso = Rol::DentalPro[$rolPermiso];
            break;

        default:
            SetLog(Operacion::Seguridad, "$username_session intento acceder con una tipologia inexistente  [" . GetIP() . "]");
            echo "<script>alert('no tienes permiso para acceder a esta pagina');</script>";
            header("Location: $urlPermisoNegado");
            exit;
    }

    // si el usuario no tiene permiso para acceder lo redirecciono
    if ($idRolSession < $idRolPermiso) {
        SetLog(Operacion::Acceso, "$username_session intento acceder a una pagina restringida  [" . GetIP() . "]");
        echo "<script>alert('no tienes permiso para acceder a esta pagina');</script>";
        header("Location: $urlPermisoNegado");
        exit;
    }
}

// funcion que evita el acceso no autorizado a las paginas del control,
// sirve para evitar bots y scrapers
function permisoControl()
{
    // si la session no es valida no le permito el acceso
    if (!isLogado()) {
        SetLog(Operacion::Backend, 'se intento acceder de forma invalida con una session no iniciada [' . GetIP() . ']');
        exit;
    }
}

// funcion que valida si un usuario inicio sesion o no
function isLogado()
{
    global $is_logado_session, $id_session;
    $isLogado = false;
    if ($is_logado_session === true && $id_session === $_SESSION['id']) {
        $isLogado = true;
    }
    return $isLogado;
}

// funcion que valida el token para formularios
function validateToken($token_session)
{
    $validToken = false;
    if ($token_session === $_SESSION['token']) {
        $validToken = true;
    } else {
        SetLog(Operacion::Seguridad, 'Envio de formulario con token invalido [' . GetIP() . ']');
    }
    return $validToken;
}
