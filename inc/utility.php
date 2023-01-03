<?php
include("../inc/database.php");
include("../inc/session.php");

abstract class Operacion
{
    const CambioUsuario = "Cambio Usuario";
    const Registro = "Registro";
    const Acceso = "Acceso";
    const Backup = "Backup";
    const Notificacion = "Notificacion";
    const Error = "Error";
    const Backend = "Backend";

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
    const Rol = array('administrador' => 1, 'manager' => 2, 'usuario' => 3, '' => 10);

    static function getOperazioni()
    {
        $classe = new ReflectionClass(__CLASS__);
        return $classe->getConstants();
    }
}

function limpiar_texto($texto)
{
    global $dbDentalPro;
    $texto = htmlentities(trim($texto));
    $textoLimpio = mysqli_real_escape_string($dbDentalPro, $texto);
    return $textoLimpio;
}

// envia un mensaje de error desde el control
function error($mensaje)
{
    $response['status'] = 'error';
    $response['mensaje'] = $mensaje;
    echo json_encode($response);
    exit;
}

// envia un mensaje (opcional) desde el control con la posibilidad de enviar datos
function success($mensaje = '', $datos = 0)
{
    $response['status'] = 'success';
    $response['mensaje'] = $mensaje;
    if ($datos !== 0) {
        $response['datos'] = $datos;
    }
    echo json_encode($response);
    exit;
}

function setLog($operacion, $mensaje)
{
    global $dbDentalPro;
    global $username_session;
    $mensajeLimpio = limpiar_texto($mensaje);
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
    global $tipologia_session;
    global $rol_session;
    global $is_logado_session;
    global $id_session;
    global $username_session;

    if(!$dbDentalPro){
        echo "<script>alert('Hubo un problema con el rervidor... Intentalo mas tarde');</script>";
        echo "<script>window.location.href = '" . $urlPermisoNegado . "';</script>";
        exit;
    }

    // controlo que la session sea valida
    if ($is_logado_session !== true || $id_session !== $_SESSION['id']) {
        SetLog(Operacion::Acceso, 'se intento acceder de forma invalida desde [' . GetIP() . ']');
        echo "<script>alert('No tienes permiso para acceder a esta pagina');</script>";
        echo "<script>window.location.href = '" . $urlPermisoNegado . "';</script>";
        exit;
    }

    // TODO: realizar despues el control para un consultorio
    /* if($idConsultorio){
        return false;
    } */

    // controlo que el usuario tenga la tipologia seleccionada
    if (!array_key_exists($tipologia_session, $arrayTipologiaPermitida)) {
        SetLog(Operacion::Acceso, "$username_session intento acceder a una pagina restringida con la tipologia $tipologia_session  [" . GetIP() . "]");
        echo "<script>alert('no tienes permiso para acceder a esta pagina');</script>";
        echo "<script>window.location.href = '" . $urlPermisoNegado . "';</script>";
        exit;
    } else {
        $idRolSession = 0;
        // obtengo el valor del rol del permiso
        foreach (Rol::Rol as $rol => $idRol) {
            if ($arrayTipologiaPermitida[$tipologia_session] == $rol) {
                $idRolSession = $idRol;
                break;
            }
        }

        // controlo que el rol exista en la clase abstracta rol
        if (empty(Rol::Rol[$rol_session])) {
            SetLog(Operacion::Acceso, "$username_session intento acceder a una pagina restringida con el rol  $rol_session [" . GetIP() . "]");
            echo "<script>alert('No tienes permiso para acceder a esta pagina');</script>";
            echo "<script>window.location.href = '" . $urlPermisoNegado . "';</script>";
            exit;
        }

        // controlo si el valor del rol del permiso es menor al rol de la sesion
        if ($idRolSession < Rol::Rol[$rol_session]) {
            SetLog(Operacion::Acceso, "$username_session intento acceder a una pagina restringida con el rol  $rol_session [" . GetIP() . "]");
            echo "<script>alert('No tienes permiso para acceder a esta pagina');</script>";
            echo "<script>window.location.href = '" . $urlPermisoNegado . "';</script>";
            exit;
        }
    }
}

function permisoControl(){
    global $id_session;
    global $is_logado_session;

    // controlo que la session sea valida
    if ($is_logado_session !== true || $id_session !== $_SESSION['id']) {
        SetLog(Operacion::Backend, 'se intento acceder de forma invalida desde [' . GetIP() . ']');
        exit;
    }
}
