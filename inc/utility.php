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

    static function getOperazioni()
    {
        $classe = new ReflectionClass(__CLASS__);
        return $classe->getConstants();
    }
}

function limpiar_texto($texto)
{
    $textoLimpio = htmlentities(trim(addslashes($texto)));
    return $textoLimpio;
}

function error($mensaje)
{
    $response['status'] = 'error';
    $response['mensaje'] = $mensaje;
    echo json_encode($response);
    exit;
}

function success($mensaje = '', $datos = -1)
{
    $response['status'] = 'success';
    $response['mensaje'] = $mensaje;
    if ($datos !== -1) {
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

function stampa($dato = 'nessun dato da stampare')
{
    echo '<pre>';
    print_r($dato);
    echo '</pre>';
}



function permiso()
{
}
