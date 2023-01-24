<?php
session_start();
include("../inc/utility.php");
include("../inc/database.php");



$operacion = limpiarTexto($_POST['operacion'] ?? '');

switch ($operacion) {
    case 'registro':
        $nombre = limpiarTexto($_POST['nombre'] ?? '');
        $apellido = limpiarTexto($_POST['apellido'] ?? '');
        $email = limpiarTexto($_POST['email'] ?? '');
        $username = limpiarTexto($_POST['username'] ?? '');
        $password = limpiarTexto($_POST['password'] ?? '');
        $confirmPassword = limpiarTexto($_POST['confirmPassword'] ?? '');

        // control sobre los datos de entrada
        if (strlen($nombre) < 5 || strlen($nombre) > 20) {
            error('EL nombre debe tener mas de 5 caracteres y menos de 20');
        }
        if (strlen($password) < 8 || strlen($password) > 32) {
            error('la contraseña debe tener mas de 8 caracteres y menos de 32');
        }
        if ($password !== $confirmPassword) {
            error('las contraseñas no coinciden');
        }
        if ($nombre == '') {
            error('debes escribir un nombre');
        }
        if ($apellido == '') {
            error('debes escribir tu apellido');
        }
        if ($email == '') {
            error('debes escribir un email');
        }
        if ($username == '') {
            error('debes escribir un nombre de usuario');
        }

        // control sobre los datos existentes
        $sql = "SELECT username FROM usuario WHERE username = '$username';";
        $loop = mysqli_query($dbDentalPro, $sql);
        $rowCount = mysqli_num_rows($loop);
        if ($rowCount > 0) {
            error('Nombre de usuario ya existente');
        }

        $sql = "SELECT email FROM usuario WHERE email = '$email';";
        $loop = mysqli_query($dbDentalPro, $sql);
        $rowCount = mysqli_num_rows($loop);
        if ($rowCount > 0) {
            error('El correo ya esta registrado');
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuario (username, password, nombre, apellido, email) VALUES('$username', '$passwordHash', '$nombre', '$apellido', '$email');";
        mysqli_query($dbDentalPro, $sql);

        // insertar un log aqui
        setLog(Operacion::Registro, "$username ha realizado el registro ( $email ) [".GetIP()."]");
        success('El registro se realizo correctamente');
        return;
    default:
        error('error');
        return;
}
