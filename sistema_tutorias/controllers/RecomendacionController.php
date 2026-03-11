<?php

require_once "../config/conexion.php";
require_once "../models/Video.php";

$materia = $_POST['materia'];

$videoModel = new Video($conexion);

$videos = $videoModel->getVideosPorMateria($materia);

include "../views/tutorias/recomendar_videos.php";