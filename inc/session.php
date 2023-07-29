<?php
include_once('database.php');

if (session_status() !== PHP_SESSION_NONE) {
    return;
}

session_start();

// variables globales para usar en todas las paginas 
$id_session = ''; // establecido en la cadena vacia para verificar la sesion activa
$is_logado_session = false; // establecido en falso para verificar la sesion activa
$id_usuario_session = 0; // actualizo el id del usuario en caso de cambio
$token_session=''; // token para validar formularios
$email_session = '';
$username_session = '';
$tipologia_session = '';
$rol_session = '';

if (isset($_SESSION['id_usuario']) && isset($_SESSION['is_logado'])&& isset($_SESSION['id'])) {
    if ($_SESSION['id_usuario'] > 0 && $_SESSION['is_logado'] === true && !empty($_SESSION['id'])) {
        // regenera el id de la sesion para evitar el secuestro de sesion
        session_regenerate_id();
        $_SESSION['id'] = $id_session = session_id();
        $_SESSION['is_logado'] = $is_logado_session = true;
        $id_usuario_session = $_SESSION['id_usuario'];

        // actualizo el token para validar formularios (se genera uno nuevo cada vez)
        $sql = "UPDATE usuario SET token = UUID_TO_BIN(UUID()) WHERE id = $id_usuario_session;";
        mysqli_query($dbDentalPro, $sql);

        $sql = "SELECT email, username, tipologia, rol, BIN_TO_UUID(token) token FROM usuario WHERE id = $id_usuario_session";
        $loop = mysqli_query($dbDentalPro, $sql);
        while ($row = mysqli_fetch_assoc($loop)) {
            $email_session = $row['email'];
            $username_session = strtolower($row['username']);
            $tipologia_session = strtolower($row['tipologia']);
            $rol_session = $row['rol'];
            $_SESSION['token'] = $token_session = $row['token'];
        }
    } else {
        $_SESSION['id'] = $id_session = '';
        $_SESSION['is_logado'] = $is_logado_session = false;
        $_SESSION['id_usuario'] = $id_usuario_session = 0;
        session_unset();
        session_destroy();
    }
} else {
    $_SESSION['id'] = $id_session = '';
    $_SESSION['is_logado'] = $is_logado_session = false;
    $_SESSION['id_usuario'] = $id_usuario_session = 0;
    session_unset();
    session_destroy();
}

session_write_close(); // evito que se sobreescriban las variables de sesion
