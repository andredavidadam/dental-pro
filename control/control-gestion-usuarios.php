<?php
include_once("../inc/session.php");
include_once("../inc/database.php");
include_once("../inc/utility.php");

$token = $_POST['token'];
if (!validateToken($token)) {
    SetLog(Operacion::Seguridad, 'Envio de formulario con token invalido [' . GetIP() . ']');
    exit;
}

$operacion = limpiarTexto($_POST['operacion'] ?? '');

switch ($operacion) {
    case 'actualizaDatos':
        // print_r($_POST);
        // exit;
        $nombre = limpiarTexto($_POST['modalNombre'] ?? '');
        $apellido = limpiarTexto($_POST['modalApellido'] ?? '');
        $email = limpiarTexto($_POST['modalEmail'] ?? '');
        $username = limpiarTexto($_POST['modalUsername'] ?? '');
        $telefono = limpiarTexto($_POST['modalTelefono'] ?? '');

        // control del nombre
        if (empty($nombre)) {
            error('Debes introducir un nombre');
        }
        if (strlen($nombre) < 3 || strlen($nombre) > 20) {
            error('El nombre debe tener mas de 3 caracteres y menos de 20');
        }

        // control del apellido
        if (empty($apellido)) {
            error('Debes introducir un apellido');
        }
        if (strlen($apellido) < 3 || strlen($apellido) > 20) {
            error('El apellido debe tener mas de 3 caracteres y menos de 20');
        }

        // control del email
        if (empty($email)) {
            error('Debes introducir un email');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            error('El formato del email no es valido');
        }
        $sql = "SELECT email FROM usuario WHERE email = '$email';";
        $loop = mysqli_query($dbDentalPro, $sql);
        $rowCount = mysqli_num_rows($loop);
        if ($rowCount > 0) {
            error('El correo ya esta registrado');
        }

        // control del username
        if (empty($apellido)) {
            error('Debes introducir un nombre de usuario');
        }
        $sql = "SELECT username FROM usuario WHERE username = '$username';";
        $loop = mysqli_query($dbDentalPro, $sql);
        $rowCount = mysqli_num_rows($loop);
        if ($rowCount > 0) {
            error('Nombre de usuario ya existente');
        }

        $oldUsername = $username_session;
        $sql = "UPDATE usuario SET nombre = '$nombre', apellido = '$apellido', email = '$email', username = '$username', telefono= '$telefono' WHERE id = $id_usuario_session;";
        mysqli_query($dbDentalPro, $sql);
        setLog(Operacion::CambioUsuario, "El usuario $oldUsername actualizo sus datos personales");
        exit;
    default:
        error('error');
        break;
}
