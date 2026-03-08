<?php

require_once "../config/conexion.php";
require_once "../models/Materia.php";

$model = new Materia($conexion);

$accion = $_GET['accion'] ?? '';

/* =========================
   CREAR MATERIA
========================= */

if($accion == "crear"){

    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];

    $model->crear($nombre,$descripcion);

    header("Location: ../views/materias/gestionar_materia.php?msg=creado");
    exit();
}


/* =========================
   ASIGNAR PROFESOR A MATERIA
========================= */

/* =========================
   ASIGNAR PROFESOR A MATERIA
========================= */

if($accion == "asignar"){

$id_profesor = $_POST['id_profesor'];
$id_materia = $_POST['id_materia'];

/* VALIDAR SI YA EXISTE */

$validar = $conexion->prepare("
SELECT 1
FROM tb_profesor_materia
WHERE id_profesor = ? AND id_materia = ?
");

$validar->bind_param("ii",$id_profesor,$id_materia);
$validar->execute();
$result = $validar->get_result();

if($result->num_rows == 0){

$stmt = $conexion->prepare("
INSERT INTO tb_profesor_materia (id_profesor,id_materia)
VALUES (?,?)
");

$stmt->bind_param("ii",$id_profesor,$id_materia);
$stmt->execute();

}

header("Location: ../views/materias/gestionar_materia.php");
exit();

}


/* =========================
   OBTENER PROFESORES POR MATERIA (AJAX)
========================= */

if($accion == "profesores"){

    $id_materia = $_GET['id_materia'];

    $result = $model->profesoresPorMateria($id_materia);

    $data = [];

    while($row = $result->fetch_assoc()){
        $data[] = $row;
    }

    echo json_encode($data);

    exit();
}