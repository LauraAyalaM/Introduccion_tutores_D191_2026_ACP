<?php

require_once "../config/conexion.php";
require_once "../models/Reserva.php";

session_start();

$model = new Reserva($conexion);

$accion = $_GET['accion'] ?? '';

/* ======================
CREAR RESERVA
====================== */

if($accion == "crear"){

    $id_tutoria = $_GET['id_tutoria'];
    $id_estudiante = $_SESSION['id_usuario'];

    if($model->crear($id_tutoria,$id_estudiante)){

        header("Location: ../views/reservas/mis_reservas.php?msg=creado");
        exit();

    }

}


/* ======================
CANCELAR RESERVA
====================== */

if($accion == "cancelar"){

    $id = $_GET['id'];

    if($model->cancelar($id)){

        header("Location: ../views/reservas/mis_reservas.php?msg=estado");
        exit();

    }

}

header("Location: ../views/reservas/mis_reservas.php?msg=error");
exit();