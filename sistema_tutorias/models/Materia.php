<?php

class Materia {

    private $conexion;

    public function __construct($conexion){
        $this->conexion = $conexion;
    }

    /* =========================
       OBTENER TODAS LAS MATERIAS
    ========================= */

    public function obtener(){

        $sql = "SELECT * FROM tb_materias ORDER BY nombre ASC";

        return $this->conexion->query($sql);
    }


    /* =========================
       CREAR MATERIA
    ========================= */

    public function crear($nombre,$descripcion){

        $stmt = $this->conexion->prepare("
            INSERT INTO tb_materias(nombre,descripcion)
            VALUES(?,?)
        ");

        $stmt->bind_param("ss",$nombre,$descripcion);

        return $stmt->execute();
    }


    /* =========================
       ASIGNAR PROFESOR A MATERIA
    ========================= */

    public function asignarProfesor($id_profesor,$id_materia){

        $stmt = $this->conexion->prepare("
            INSERT INTO tb_profesor_materia(id_profesor,id_materia)
            VALUES(?,?)
        ");

        $stmt->bind_param("ii",$id_profesor,$id_materia);

        return $stmt->execute();
    }


    /* =========================
       PROFESORES POR MATERIA
    ========================= */

    public function profesoresPorMateria($id_materia){

        $stmt = $this->conexion->prepare("
            SELECT u.id_usuario, u.nombre
            FROM tb_profesor_materia pm
            INNER JOIN tb_usuarios u
            ON pm.id_profesor = u.id_usuario
            WHERE pm.id_materia = ?
        ");

        $stmt->bind_param("i",$id_materia);

        $stmt->execute();

        return $stmt->get_result();
    }

}