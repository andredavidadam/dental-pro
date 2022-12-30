<?php
session_start();

$_SESSION['id_session'] = '';
$_SESSION["is_logado_session"] = false;
$_SESSION["id_usuario_session"] = 0;
$_SESSION['token_session'] = '';

$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();
session_unset();
session_write_close();

if (isset($_SESSION)) {
    echo "la sesion no fue destruida";
} else {
    echo "la sesion fue destruida";
}
