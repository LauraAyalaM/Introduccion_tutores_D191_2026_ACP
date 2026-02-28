<?php
session_start();
require_once "../config/conexion.php";

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit();
}

$id_reserva = $_GET['id'];
$id_estudiante = $_SESSION['id_usuario'];

$sql = "UPDATE tb_reservas 
        SET estado = 'cancelada' 
        WHERE id_reserva = ? 
        AND id_estudiante = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $id_reserva, $id_estudiante);

if ($stmt->execute()) {
    header("Location: mis_reservas.php");
    exit();
} else {
    echo "Error al cancelar la reserva";
}
?>
