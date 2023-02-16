<?php
session_start();
include("../inc/utility.php");
include("../inc/database.php");

$operacion = limpiarTexto($_POST['operacion'] ?? '');

switch ($operacion) {
    case 'registro':
        $nombre = strtolower(limpiarTexto($_POST['nombre'] ?? ''));
        $apellido = strtolower(limpiarTexto($_POST['apellido'] ?? ''));
        $email = limpiarTexto($_POST['email'] ?? '');
        $username = limpiarTexto($_POST['username'] ?? '');
        $telefono = limpiarTexto($_POST['telefono'] ?? '');
        $password = limpiarTexto($_POST['password'] ?? '');
        $confirmPassword = limpiarTexto($_POST['confirmPassword'] ?? '');

        // control sobre los datos de entrada
        if (empty($nombre)) {
            response('warning', 'Introduce un nombre.');
        } else if (strlen($nombre) < 3 || strlen($nombre) > 20) {
            response('warning', 'El nombre debe tener mas de 3 letras y menos de 20.');
        }

        if (empty($apellido)) {
            response('warning', 'Introduce un apellido.');
        } else if (strlen($apellido) < 3 || strlen($apellido) > 20) {
            response('warning', 'El apellido debe tener mas de 3 letras y menos de 20.');
        }

        $regex = '/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';
        if (empty($email)) {
            response('warning', 'Introduce un email.');
        } else if (!preg_match($regex, $email)) {
            response('warning', 'Escribe un email valido.');
        }

        if (empty($username)) {
            response('warning', 'Introduce un username.');
        } else if (strlen($username) < 5 || strlen($username) > 20) {
            response('warning', 'El username debe tener mas de 3 letras y menos de 20.');
        }

        if (strlen($telefono) < 7 && strlen($telefono)!=0) {
            response('warning', 'Introduce un numero telefonico valido o deja el campo vacio.');
        }

        $regex = '/^(?=.*[A-Za-z])(?=.*\d)(?=.*[\.\_\$]).{10,32}$/';
        if (!preg_match($regex, $password)) {
            response('warning', 'Introduce una contraseña de almenos 10 caracteres con letras y numeros y los simbolos ". _ $" sin espacios en blanco.');
        } else if ($password !== $confirmPassword) {
            response('warning', 'Las contraseñas no coinciden.');
        }

        // control sobre los datos existentes
        $sql = "SELECT username FROM usuario WHERE username = '$username';";
        $loop = mysqli_query($dbDentalPro, $sql);
        $rowCount = mysqli_num_rows($loop);
        if ($rowCount > 0) {
            response('warning', 'Nombre de usuario ya existente.');
        }

        $sql = "SELECT email FROM usuario WHERE email = '$email';";
        $loop = mysqli_query($dbDentalPro, $sql);
        $rowCount = mysqli_num_rows($loop);
        if ($rowCount > 0) {
            response('warning', 'El correo ya esta registrado.');
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuario (username, password, nombre, apellido, email, telefono) VALUES('$username', '$passwordHash', '$nombre', '$apellido', '$email', '$telefono');";
        mysqli_query($dbDentalPro, $sql);
        setLog(Operacion::Registro, "$username ha realizado el registro ( $email ) [" . GetIP() . "]");
        response('success', 'El registro se realizo correctamente <br> Ahora puedes iniciar sesion');
        exit;
    default:
        response('error','operacion inexistente');
        return;
}
