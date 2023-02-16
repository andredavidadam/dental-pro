<?php
include("../inc/session.php");
include("../inc/database.php");
include("../inc/utility.php");

// print_r($_POST['token'].' = '.$_SESSION['token']);
print_r($_POST['token'].' = '.$_SESSION['token']);
return;

$operacion = limpiarTexto($_POST['operacion'] ?? '');

switch ($operacion) {
    case 'actualizarDatos':
        $nombre = limpiarTexto($_POST['nombre'] ?? '');
        $apellido = limpiarTexto($_POST['apellido'] ?? '');
        $email = limpiarTexto($_POST['email'] ?? '');
        $username = limpiarTexto($_POST['username'] ?? '');
        $telefono = limpiarTexto($_POST['telefono'] ?? '');

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

        if (strlen($telefono) < 7 && strlen($telefono) != 0) {
            response('warning', 'Introduce un numero telefonico valido o deja el campo vacio.');
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

        $oldUsername = $username_session;
        $sql = "UPDATE usuario SET nombre = '$nombre', apellido = '$apellido', email = '$email', username = '$username', telefono= '$telefono' WHERE id = $id_usuario_session;";
        mysqli_query($dbDentalPro, $sql);
        setLog(Operacion::CambioUsuario, "El usuario $oldUsername actualizo sus datos personales");
        response('success', 'Los datos se actualizaron correctamente');
        exit;
    default:
        response('error', 'operacion inexistente');
        break;
}
