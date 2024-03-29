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
        $tipologia = limpiarTexto($_POST['tipologia'] ?? '');
        $rol = limpiarTexto($_POST['rol'] ?? '');

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

        if(!in_array($tipologia,Tipologia::getTipologias())){
            response('warning', 'Tiplogia invalida');
        }

        if(!in_array($rol,Rol::getRoles())){
            response('warning', 'Rol invalido');
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
        $sql = "UPDATE usuario SET nombre = '$nombre', apellido = '$apellido', email = '$email', username = '$username', telefono= '$telefono', tipologia='$tipologia', rol='$rol' WHERE id = $id_usuario_session;";
        mysqli_query($dbDentalPro, $sql);
        setLog(Operacion::CambioUsuario, "El sistema actualizo los datos personales de $oldUsername");

        response('success', 'Los datos se actualizaron correctamente');
        
        exit;
    case 'modalGetDatos':
        if (!isLogado()) {
            return;
        }

        $idUsuario = limpiarTexto($_POST['usuario'] ?? 0);

        if ($idUsuario < 1) {
            response('warning', 'Usuario inexistente.');
        }

        $sql = "SELECT username, nombre, apellido, email, telefono, tipologia, rol FROM usuario WHERE id = $idUsuario;";
        $loop = mysqli_query($dbDentalPro, $sql);
        while ($row = mysqli_fetch_assoc($loop)) {
            $nombre = $row["nombre"];
            $apellido = $row['apellido'];
            $username = $row['username'];
            $email = $row['email'];
            $telefono = $row['telefono'];
            $tipologia = $row['tipologia'];
            $rol = $row['rol'];
        }

        $arrayResponse = ['nombre' => $nombre, 'apellido' => $apellido, 'username' => $username, 'email' => $email, 'telefono' => $telefono, 'tipologia' => $tipologia, 'rol' => $rol];

        response('success', '', $arrayResponse);
        exit;
    default:
        response('error', 'operacion inexistente');
        break;
}
