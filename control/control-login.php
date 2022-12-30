<?php
session_start();
include("../inc/utility.php");
include("../inc/database.php");

$operacion = $_POST['operacion'] ?? '';

switch ($operacion) {
    case 'login':
        $username = limpiar_texto($_POST['username'] ?? '');
        $password = limpiar_texto($_POST['password'] ?? '');

        if (empty($username) || empty($password)) {
            error('datos incorrectos');
        }

        $sql = "SELECT password FROM usuario WHERE username = '$username';";
        $loop = mysqli_query($dbDentalPro, $sql);
        while ($row = mysqli_fetch_assoc($loop)) {
            $passwordHash = $row['password'] ?? '';
        }
        if ($passwordHash == "") {
            error('usuario incorrecto');
        }

        if (password_verify($password, $passwordHash)) {
            $sql = "SELECT usuario.id FROM usuario WHERE username = '$username';";
            $loop = mysqli_query($dbDentalPro, $sql);
            while ($row = mysqli_fetch_assoc($loop)) {
                $idUsuario = $row['id'] ?? 0;
            }
            $_SESSION["is_logado"] = true;
            $_SESSION["id_usuario"] = $idUsuario;

            $username = "";
            $email = "";
            $sql = "SELECT usuario.username, usuario.email FROM usuario WHERE usuario.id = $idUsuario;";
            $loop = mysqli_query($dbDentalPro, $sql);
            while ($row = mysqli_fetch_assoc($loop)) {
                $username = $row["username"];
                $email = $row["email"];
            }

            SetLog(Operacion::Acceso, "$username ha realizado el acceso ( $email ) [" . GetIP() . "]");
            $sql = "UPDATE usuario SET data_ultimo_acceso = now() WHERE id =  $idUsuario;";
            $loop = mysqli_query($dbDentalPro, $sql);
            success();
        } else {
            error('password incorrecta');
        }
        return;
    default:
        //$mensaje = 'operacion no encontrada';
        error('error');
        
}
