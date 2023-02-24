<?php
include("../inc/session.php");
include("../inc/database.php");
include("../inc/utility.php");

$operacion = limpiarTexto($_POST['operacion'] ?? '');

switch ($operacion) {
    case 'actualizarDatos':
        permisoControl($_POST['token']);

        $nombre = limpiarTexto(strtolower($_POST['nombre'] ?? ''));
        $apellido = limpiarTexto(strtolower($_POST['apellido'] ?? ''));
        $email = limpiarTexto($_POST['email'] ?? '');
        $username = limpiarTexto($_POST['username'] ?? '');
        $telefono = limpiarTexto($_POST['telefono'] ?? '');

        // control sobre los datos de entrada
        if (empty($nombre)) {
            response('warning', 'Introduce un nombre.');
        } else if (strlen($nombre) < 3 || strlen($nombre) > 40) {
            response('warning', 'El nombre debe tener mas de 3 letras y menos de 40.');
        }

        if (empty($apellido)) {
            response('warning', 'Introduce un apellido.');
        } else if (strlen($apellido) < 3 || strlen($apellido) > 40) {
            response('warning', 'El apellido debe tener mas de 3 letras y menos de 40.');
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
        if ($username != $username_session) {
            $sql = "SELECT username FROM usuario WHERE username = '$username';";
            $loop = mysqli_query($dbDentalPro, $sql);
            $rowCount = mysqli_num_rows($loop);
            if ($rowCount > 0) {
                response('warning', 'Nombre de usuario ya existente.');
            }
        }

        if ($email != $email_session) {
            $sql = "SELECT email FROM usuario WHERE email = '$email';";
            $loop = mysqli_query($dbDentalPro, $sql);
            $rowCount = mysqli_num_rows($loop);
            if ($rowCount > 0) {
                response('warning', 'El correo ya esta registrado.');
            }
        }

        $oldUsername = $username_session;
        $sql = "UPDATE usuario SET nombre = '$nombre', apellido = '$apellido', email = '$email', username = '$username', telefono= '$telefono' WHERE id = $id_usuario_session;";
        mysqli_query($dbDentalPro, $sql);
        setLog(Operacion::CambioUsuario, "El usuario $oldUsername actualizo sus datos personales");

        $sql = "SELECT username, nombre, apellido, email, telefono, tipologia, rol FROM usuario WHERE id = $id_usuario_session;";
        $loop = mysqli_query($dbDentalPro, $sql);
        while ($row = mysqli_fetch_assoc($loop)) {
            $username = $row['username'];
            $nombre = ucwords($row['nombre']);
            $apellido = ucwords($row['apellido']);
            $email = $row['email'];
            $telefono = $row['telefono'];
            $tipologia = ucfirst($row['tipologia']);
            $rol = ucfirst($row['rol']);
        }

        $arrayResponse = ['username' => $username, 'nombre' => $nombre, 'apellido' => $apellido, 'email' => $email, 'telefono' => $telefono, 'tipologia' => $tipologia, 'rol' => $rol];

        response('success', 'Los datos se actualizaron correctamente', $arrayResponse);
        exit;
    case 'actualizarPassword':
        permisoControl($_POST['token']);

        $passwordActual = limpiarTexto($_POST['passwordActual'] ?? '');
        $nuevaPassword = limpiarTexto($_POST['nuevaPassword'] ?? '');
        $confirmaNuevaPassword = limpiarTexto($_POST['confirmaNuevaPassword'] ?? '');

        // controlo si las contraseñas nuevas son iguales
        $regex = '/^(?=.*[A-Za-z])(?=.*\d)(?=.*[\.\_\$]).{10,32}$/';
        if (!preg_match($regex, $nuevaPassword)) {
            response('warning', 'Introduce una contraseña de almenos 10 caracteres con letras y numeros y los simbolos ". _ $" sin espacios en blanco.');
        } else if ($nuevaPassword !== $confirmaNuevaPassword) {
            response('warning', 'Las contraseñas no coinciden.');
        }

        $sql = "SELECT password FROM usuario WHERE username = '$username_session';";
        $loop = mysqli_query($dbDentalPro, $sql);
        while ($row = mysqli_fetch_assoc($loop)) {
            $passwordHash = $row['password'] ?? '';
        }
        if ($passwordHash == "") {
            response('warning', 'usuario incorrecto');
        }

        // controlo si la password es correcta
        if (!password_verify($passwordActual, $passwordHash)) {
            response('warning', 'password incorrecta');
        }

        $nuevaPasswordHash = password_hash($nuevaPassword, PASSWORD_DEFAULT);

        setLog(Operacion::CambioUsuario, "El usuario $username_session cambio su contraseña");
        $sql = "UPDATE usuario SET password = $nuevaPasswordHash WHERE id = $id_usuario_session;";
        //mysqli_query($dbDentalPro, $sql);
        response('success', 'Se actualizo la password correctamente');
        exit;
    default:
        response('error', 'operacion inexistente');
        break;
}
