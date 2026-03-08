<?php

session_start();

require_once "../config/conexion.php";
require_once "../models/Tutoria.php";

$model = new Tutoria($conexion);

$accion = $_GET['accion'] ?? '';

/* =========================
   CREAR TUTORIA
========================= */

if($accion == "crear"){

    if(!isset($_SESSION['id_usuario'])){
        header("Location: ../login.php");
        exit();
    }

    if(!isset(
        $_POST['id_materia'],
        $_POST['fecha'],
        $_POST['hora_inicio'],
        $_POST['hora_fin'],
        $_POST['cupos']
    )){
        header("Location: ../views/tutorias/listar.php?msg=error");
        exit();
    }

    $id_profesor = $_SESSION['id_usuario'];
    $id_materia = $_POST['id_materia'];
    $fecha = $_POST['fecha'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fin = $_POST['hora_fin'];
    $cupos = $_POST['cupos'];

    $estado = "disponible";

    if($model->crear(
        $id_profesor,
        $id_materia,
        $fecha,
        $hora_inicio,
        $hora_fin,
        $cupos,
        $estado
    )){

        header("Location: ../views/tutorias/listar.php?msg=creado");
        exit();

    }else{

        header("Location: ../views/tutorias/listar.php?msg=error");
        exit();
    }

}

/* =========================
   ACTUALIZAR TUTORIA
========================= */

elseif($accion == "actualizar"){

    if(!isset($_SESSION['id_usuario'])){
        header("Location: ../login.php");
        exit();
    }

    if(!isset(
        $_POST['id_tutoria'],
        $_POST['fecha'],
        $_POST['hora_inicio'],
        $_POST['hora_fin'],
        $_POST['cupos']
    )){
        header("Location: ../views/tutorias/listar.php?msg=error");
        exit();
    }

    $id = $_POST['id_tutoria'];
    $id_profesor = $_SESSION['id_usuario'];
    $fecha = $_POST['fecha'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fin = $_POST['hora_fin'];
    $cupos = $_POST['cupos'];

    if($model->actualizar(
        $id,
        $id_profesor,
        $fecha,
        $hora_inicio,
        $hora_fin,
        $cupos
    )){

        header("Location: ../views/tutorias/listar.php?msg=editado");
        exit();

    }else{

        header("Location: ../views/tutorias/listar.php?msg=error");
        exit();
    }

}

/* =========================
   CAMBIAR ESTADO
========================= */

elseif($accion == "estado"){

    if(!isset($_GET['id'], $_GET['estado'])){
        header("Location: ../views/tutorias/listar.php?msg=error");
        exit();
    }

    $id = $_GET['id'];
    $estado = $_GET['estado'];

    if($model->cambiarEstado($id,$estado)){

        header("Location: ../views/tutorias/listar.php?msg=estado");
        exit();

    }else{

        header("Location: ../views/tutorias/listar.php?msg=error");
        exit();
    }

}

/* =========================
   ACCION NO VALIDA
========================= */

else{

    header("Location: ../views/tutorias/listar.php?msg=error");
    exit();

}