<?php
session_start();
include "../config/db.php";

if(isset($_GET['id_reserva'])){

    $id_reserva = $_GET['id_reserva'];

    // Obtener tutoría asociada
    $reserva = $conn->query("
        SELECT id_tutoria 
        FROM tb_reservas
        WHERE id_reserva=$id_reserva
    ")->fetch_assoc();

    $id_tutoria = $reserva['id_tutoria'];

    // Cancelar reserva
    $conn->query("
        UPDATE tb_reservas
        SET estado='cancelada'
        WHERE id_reserva=$id_reserva
    ");

    // Devolver cupo
    $conn->query("
        UPDATE tb_tutorias
        SET cupos = cupos + 1
        WHERE id_tutoria=$id_tutoria
    ");

    header("Location: ../views/reservas.php");
}
?>