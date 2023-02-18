<?php
include_once('database.php');

if (session_status() !== PHP_SESSION_NONE) {
    return;
}

session_start();

/* --- VARIABILI GLOBALI --- */
$id_session = '';
$is_logado_session = false;
$id_usuario_session = 0; // Mi serve per aggiornare ogni volta i dati dell'utente (in caso di cambiamento)
$email_session = '';
$username_session = '';
$tipologia_session = '';
$rol_session = '';

if (isset($_SESSION['id_usuario']) && isset($_SESSION['is_logado'])) {
    if ($_SESSION['id_usuario'] > 0 && $_SESSION['is_logado'] === true) {
        session_regenerate_id();
        $_SESSION['id'] = $id_session = session_id(); // controla si tengp permisos para acceder a la pagina
        $_SESSION['is_logado'] = $is_logado_session = true;
        $id_usuario_session = $_SESSION['id_usuario'];
        $sql = "SELECT email, username, tipologia, rol FROM usuario WHERE id = $id_usuario_session";
        $loop = mysqli_query($dbDentalPro, $sql);
        while ($row = mysqli_fetch_assoc($loop)) {
            $email_session = $row['email'];
            $username_session = strtolower($row['username']);
            $tipologia_session = strtolower($row['tipologia']);
            $rol_session = $row['rol'];
        }
    } else {
        $_SESSION['is_logado'] = $is_logado_session = false;
        $_SESSION['id_usuario'] = $id_usuario_session = 0;
        session_unset();
    }
} else {
    $_SESSION['is_logado'] = $is_logado_session = false;
    $_SESSION['id_usuario'] = $id_usuario_session = 0;
    session_unset();
}

//session_write_close();


