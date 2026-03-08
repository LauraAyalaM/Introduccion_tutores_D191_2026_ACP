<?php
require_once "../includes/auth.php";
require_once "../config/conexion.php";

if ($_SESSION['rol'] !== "administrador") {
    header("Location: ../login.php");
    exit();
}

$id_usuario = $_GET['id'] ?? null;
$estado = $_GET['estado'] ?? null;

if ($id_usuario !== null && ($estado === "0" || $estado === "1")) {
    $stmt = $conexion->prepare("UPDATE tb_usuarios SET activo = ? WHERE id_usuario = ?");
    $stmt->bind_param("ii", $estado, $id_usuario);
    $stmt->execute();
    $stmt->close();
}

header("Location: listar.php");
exit();