<?php
session_start();
include "../config/db.php";

// Solo estudiantes pueden reservar (simple control)

if(isset($_GET['id_tutoria'])){

    $id_tutoria = $_GET['id_tutoria'];
    $id_estudiante = $_SESSION['usuario']['id_usuario'];

    // Verificar cupos y estado
    $check = $conn->query("
        SELECT cupos, estado
        FROM tb_tutorias
        WHERE id_tutoria=$id_tutoria
    ")->fetch_assoc();

    if($check['estado'] != "disponible" || $check['cupos'] <= 0){
        die("Tutoría no disponible");
    }

    // Crear reserva
    $conn->query("
        INSERT INTO tb_reservas(id_tutoria,id_estudiante,estado)
        VALUES($id_tutoria,$id_estudiante,'activa')
    ");

    // Reducir cupos
    $conn->query("
        UPDATE tb_tutorias
        SET cupos = cupos - 1
        WHERE id_tutoria=$id_tutoria
    ");

    header("Location: ../views/tutorias.php");
}
?>