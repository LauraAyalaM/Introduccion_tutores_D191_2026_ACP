<?php
// includes/auth.php

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verifica si el usuario está autenticado
 */
function verificarSesion() {
    if (!isset($_SESSION['id_usuario'])) {
        header("Location: /sistema_tutorias/login.php");
        exit();
    }
}

if (isset($_SESSION['tiempo'])) {
    $inactividad = 1800; // 30 min
    if (time() - $_SESSION['tiempo'] > $inactividad) {
        session_unset();
        session_destroy();
    }
}
$_SESSION['tiempo'] = time();
/**
 * Verifica si el usuario tiene un rol específico
 */
function verificarRol($rolPermitido) {
    if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== $rolPermitido) {
        header("Location: /sistema_tutorias/index.php");
        exit();
    }
}

/**
 * Verifica múltiples roles permitidos
 */
function verificarRoles($rolesPermitidos = []) {
    if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], $rolesPermitidos)) {
        header("Location: /sistema_tutorias/index.php");
        exit();
    }
}
?>