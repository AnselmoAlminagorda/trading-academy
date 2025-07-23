<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requiereLogin() {
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: ../login.php");
        exit;
    }

    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Expires: 0");
    header("Pragma: no-cache");
}

function esAdmin() {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
}
