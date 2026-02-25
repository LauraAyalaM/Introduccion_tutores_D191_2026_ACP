<?php
require_once "../includes/auth.php";
require_once "../config/conexion.php";

// Solo administrador
if ($_SESSION['rol'] !== "administrador") {
    header("Location: ../login.php");
    exit();
}

$id_tutoria = $_GET['id'] ?? null;
$estado = $_GET['estado'] ?? null;

if ($id_tutoria && in_array($estado, ['disponible', 'cancelada'])) {
    $stmt = $conexion->prepare("UPDATE tb_tutorias SET estado = ? WHERE id_tutoria = ?");
    $stmt->bind_param("si", $estado, $id_tutoria);
    $stmt->execute();
    $stmt->close();
}

// Redirigir al listado
header("Location: listar.php");
exit();
?>