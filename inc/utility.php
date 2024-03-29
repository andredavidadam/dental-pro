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

    static function getOperaciones()
    {
        $classe = new ReflectionClass(__CLASS__);
        return $classe->getConstants();
    }
}

abstract class Tipologia
{
    const Rainweb = "rainweb";
    const DentalPro = "dentalpro";


    static function getTipologias()
    {
        $classe = new ReflectionClass(__CLASS__);
        return $classe->getConstants();
    }
}

abstract class Rol
{
    const Administrador = 'administrador';
    const Manager = 'manager';
    const Usuario = 'usuario';

    static function getRoles()
    {
        $classe = new ReflectionClass(__CLASS__);
        return $classe->getConstants();
    }
}

abstract class AccessLevel
{
    const Rainweb = ['' => 1, Rol::Usuario => 2, Rol::Manager => 3,  Rol::Administrador => 4];
    const DentalPro = ['' => 1, Rol::Usuario => 2, Rol::Manager => 3,  Rol::Administrador => 4];

    static function getAccessLevels()
    {
        $classe = new ReflectionClass(__CLASS__);
        return $classe->getConstants();
    }
}

// el orden de la limpieza de la cadena es muy importante
function limpiarTexto($texto)
{
    $texto = preg_replace('/<script>.*<\/script>/isx', '', $texto); // elimina cualquier cosa que este dentro las etiquetas de apertura y cierre de javascript
    $texto = strip_tags($texto); // elimina las etiquetas html de la cadena
    $texto = str_replace(['#', '--', '\\'], '', $texto); // elimina los caracteres de inicio de un comentario en mysql y las barras invertidas
    $texto = preg_replace('/\s\s+/', ' ', $texto); // reemplaza si hay mas de 2+ espacios entre palabras en el texto por un espacio
    $texto = trim($texto); // reemplaza los espacios al inicio y al final de una cadena
    return $texto;
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
    $token = bin2hex(openssl_random_pseudo_bytes($num / 2));
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
        goToPage($urlPermisoNegado);
        exit;
    }

    // si la session no es valida lo mando al index (evita el acceso a quien no esta logado)
    if (!isLogado()) {
        SetLog(Operacion::Seguridad, 'se intento acceder de forma invalida con una session no iniciada [' . GetIP() . ']');
        echo "<script>alert('no tienes permiso para acceder a esta pagina');</script>";
        goToPage($urlPermisoNegado);
        exit;
    }

    // si se llega hasta aqui, la session se inicio correctamente

    // si la tipologia de la session no esta en el permiso lo mando al index
    // tambien redirecciono en el caso que no tenga una tipologia y rol en el bd
    if (!array_key_exists($tipologia_session, $arrayTipologiaPermitida)) {
        SetLog(Operacion::Seguridad, "$username_session intento acceder con una tipologia invalida  [" . GetIP() . "]");
        echo "<script>alert('no tienes permiso para acceder a esta pagina');</script>";
        goToPage($urlPermisoNegado);
        exit;
    }


    $idRolPermiso = 0;
    switch ($tipologia_session) {
        case Tipologia::Rainweb:
            // obtengo el valor del rol del permiso
            $idRolSession = AccessLevel::Rainweb[$rol_session];
            $rolPermiso = $arrayTipologiaPermitida[Tipologia::Rainweb];
            $idRolPermiso = AccessLevel::Rainweb[$rolPermiso];
            break;

        case Tipologia::DentalPro:
            // obtengo el valor del rol del permiso
            $idRolSession = AccessLevel::DentalPro[$rol_session];
            $rolPermiso = $arrayTipologiaPermitida[Tipologia::DentalPro];
            $idRolPermiso = AccessLevel::DentalPro[$rolPermiso];
            break;

        default:
            SetLog(Operacion::Seguridad, "$username_session intento acceder con una tipologia inexistente  [" . GetIP() . "]");
            echo "<script>alert('no tienes permiso para acceder a esta pagina');</script>";
            goToPage($urlPermisoNegado);
            exit;
    }

    // si el usuario no tiene permiso para acceder lo redirecciono
    if ($idRolSession < $idRolPermiso) {
        SetLog(Operacion::Acceso, "$username_session intento acceder a una pagina restringida  [" . GetIP() . "]");
        echo "<script>alert('no tienes permiso para acceder a esta pagina');</script>";
        goToPage($urlPermisoNegado);
        exit;
    }
}

// funcion que evita el acceso no autorizado a las paginas del control
// si no se inicia sesion o si se envia el token en los formularios 
// no se permite la operacion
// sirve para evitar bots y scrapers
function permisoControl($token)
{
    // si la session no es valida no le permito el acceso
    if (!isLogado()) {
        SetLog(Operacion::Backend, 'se intento realizar una operacion sin sesion iniciada [' . GetIP() . ']');
        // goToPage($urlPermisoNegado);
        exit;
    } else if (!validateToken($token)) {
        SetLog(Operacion::Backend, 'se envio una solicitud sin el token [' . GetIP() . ']');
        // goToPage($urlPermisoNegado);
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
function validateToken($token)
{
    $validToken = false;
    if ($token === $_SESSION['token']) {
        $validToken = true;
    } else {
        SetLog(Operacion::Seguridad, 'Envio de formulario con token invalido [' . GetIP() . ']');
    }
    return $validToken;
}

function goToPage($url)
{
    echo "<script>window.location.href = '" . $url . "';</script>";
}
