<?php

require_once "../config/conexion.php";
require_once "../models/Usuario.php";

$model = new Usuario($conexion);

$accion = $_GET['accion'] ?? '';

/* CREAR USUARIO */

if($accion == "crear"){

    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $password = trim($_POST['password']);
    $id_rol = $_POST['id_rol'];

    if($model->existeCorreo($correo)){
        header("Location: ../views/usuarios/crear.php?msg=error");
        exit();
    }

    $model->crear($nombre,$correo,$password,$id_rol);

    header("Location: ../views/usuarios/listar.php?msg=creado");
    exit();
}


/* ACTUALIZAR */

if($accion == "actualizar"){

    $id = $_POST['id_usuario'];
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $password = $_POST['password'];
    $id_rol = $_POST['id_rol'];

    $model->actualizar($id,$nombre,$correo,$password,$id_rol);

    header("Location: ../views/usuarios/listar.php?msg=editado");
    exit();
}


/* CAMBIAR ESTADO */

if($accion == "estado"){

    $id = $_GET['id'];
    $estado = $_GET['estado'];

    $model->cambiarEstado($id,$estado);

    header("Location: ../views/usuarios/listar.php?msg=estado");
    exit();
}

/* SI NO EXISTE ACCION */

header("Location: ../views/usuarios/listar.php?msg=error");
exit();