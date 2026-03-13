<?php

class Reserva {

    private $conexion;

    public function __construct($conexion){
        $this->conexion = $conexion;
    }

    /* CREAR RESERVA */

    public function crear($id_tutoria,$id_estudiante){

        $stmt = $this->conexion->prepare("
        INSERT INTO tb_reservas (id_tutoria,id_estudiante)
        VALUES (?,?)
        ");

        $stmt->bind_param("ii",$id_tutoria,$id_estudiante);

        return $stmt->execute();
    }


    /* CANCELAR */

    public function cancelar($id_reserva){

        $stmt = $this->conexion->prepare("
        UPDATE tb_reservas
        SET estado='cancelada'
        WHERE id_reserva=?
        ");

        $stmt->bind_param("i",$id_reserva);

        return $stmt->execute();
    }

}