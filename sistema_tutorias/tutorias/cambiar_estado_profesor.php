<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../models/Tutoria.php';

verificarRol('profesor');

$id_profesor = $_SESSION['id_usuario'];
$id_tutoria = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$estado = isset($_GET['estado']) ? $_GET['estado'] : null;

$allowed = ['disponible','cancelada'];
if (!$id_tutoria || !$estado || !in_array($estado, $allowed)) {
    header('Location: /Introduccion_tutores_D191_2026_ACP/sistema_tutorias/dashboard/profesor.php');
    exit();
}

$model = new Tutoria($conexion);
$t = $model->getById($id_tutoria);
if (!$t || $t['id_profesor'] != $id_profesor) {
    header('Location: /Introduccion_tutores_D191_2026_ACP/sistema_tutorias/dashboard/profesor.php');
    exit();
}

$ok = $model->cambiarEstado($id_tutoria, $estado);

header('Location: /Introduccion_tutores_D191_2026_ACP/sistema_tutorias/dashboard/profesor.php');
exit();
