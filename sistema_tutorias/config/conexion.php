<?php
// config/conexion.php

$host = "localhost";
$usuario = "root";
$password = "";
$bd = "sistema_tutorias";

$conexion = new mysqli($host, $usuario, $password, $bd);

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Configurar caracteres
$conexion->set_charset("utf8");

// Opcional: función para cerrar conexión
function cerrarConexion($conexion){
    $conexion->close();
}
?>