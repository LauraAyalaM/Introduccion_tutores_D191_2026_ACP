<?php
require_once "../../config/conexion.php";

if(isset($_GET['id']) && isset($_GET['estado'])){

$id = $_GET['id'];
$estado = $_GET['estado'];

$stmt = $conexion->prepare("
UPDATE tb_tutorias 
SET estado = ?
WHERE id_tutoria = ?
");

$stmt->bind_param("si",$estado,$id);

if($stmt->execute()){

header("Location: listar.php?msg=estado");
exit();

}else{

header("Location: listar.php?msg=error");
exit();

}

}