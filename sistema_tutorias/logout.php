<?php
session_start(); // 🔹 Asegúrate de iniciar la sesión

// Destruir todas las variables de sesión
$_SESSION = [];

// Destruir la cookie de sesión en el navegador
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000, // fecha pasada para borrar
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destruir la sesión completamente
session_destroy();

// Redirigir al login
header("Location: login.php");
exit();
?>