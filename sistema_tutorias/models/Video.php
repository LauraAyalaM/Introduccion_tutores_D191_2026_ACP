<?php

class Video {

    private $conexion;

    public function __construct($conexion){
        $this->conexion = $conexion;
    }

    public function getVideosPorMateria($id_materia){

        $stmt = $this->conexion->prepare(
            "SELECT * FROM tb_videos WHERE id_materia = ?"
        );

        $stmt->bind_param("i",$id_materia);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

}