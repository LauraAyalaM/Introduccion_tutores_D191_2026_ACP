<?php

require_once "../includes/auth.php";
require_once "../config/conexion.php";
require_once "../models/Reserva.php";

verificarRol("estudiante");

$reserva = new Reserva($conexion);

if(isset($_GET['id'])){

    $id_reserva = intval($_GET['id']);

    $reserva->cancelar($id_reserva);

}

header("Location: mis_reservas.php");
exit();