<?php

class Tutoria {

    private $conexion;

    public function __construct($conexion){
        $this->conexion = $conexion;
    }

    /* LISTAR TODAS */

    public function listar(){

        $sql = "SELECT t.*, u.nombre as profesor
                FROM tb_tutorias t
                INNER JOIN tb_usuarios u ON t.id_profesor = u.id_usuario
                ORDER BY t.fecha ASC";

        $result = $this->conexion->query($sql);

        return $result->fetch_all(MYSQLI_ASSOC);
    }


    /* OBTENER POR ID */

    public function getById($id){

        $stmt = $this->conexion->prepare("
            SELECT * FROM tb_tutorias
            WHERE id_tutoria = ?
        ");

        $stmt->bind_param("i",$id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }


    /* CREAR */

public function crear($id_profesor, $id_materia, $fecha, $hora_inicio, $hora_fin, $cupo, $estado)
{
    $sql = "INSERT INTO tb_tutorias 
            (id_profesor, id_materia, fecha, hora_inicio, hora_fin, cupos, estado) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $this->conexion->prepare($sql);

    return $stmt->execute([
        $id_profesor,
        $id_materia,
        $fecha,
        $hora_inicio,
        $hora_fin,
        $cupo,
        $estado
    ]);
}


    /* ACTUALIZAR */

public function actualizar($id,$id_profesor,$fecha,$hora_inicio,$hora_fin,$cupos){

$stmt = $this->conexion->prepare("
UPDATE tb_tutorias
SET id_profesor=?,
    fecha=?,
    hora_inicio=?,
    hora_fin=?,
    cupos=?
WHERE id_tutoria=?
");

$stmt->bind_param("isssii",
$id_profesor,
$fecha,
$hora_inicio,
$hora_fin,
$cupos,
$id
);

return $stmt->execute();
}

    /* CAMBIAR ESTADO */

    public function cambiarEstado($id,$estado){

        $stmt = $this->conexion->prepare("
            UPDATE tb_tutorias
            SET estado=?
            WHERE id_tutoria=?
        ");

        $stmt->bind_param("si",$estado,$id);

        return $stmt->execute();
    }


    /* CONTAR RESERVAS */

    public function countReservas($id){

        $stmt = $this->conexion->prepare("
            SELECT COUNT(*) as total
            FROM tb_reservas
            WHERE id_tutoria=?
        ");

        $stmt->bind_param("i",$id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc()['total'];
    }


    /* RESERVAS DETALLE */

    public function getReservasDetalles($id){

        $stmt = $this->conexion->prepare("
            SELECT u.nombre,u.correo,r.estado,r.fecha_reserva
            FROM tb_reservas r
            INNER JOIN tb_usuarios u
            ON r.id_estudiante = u.id_usuario
            WHERE r.id_tutoria=?
        ");

        $stmt->bind_param("i",$id);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }


    /* TUTORIAS DE PROFESOR */

    public function getByProfesor($id_profesor){

        $stmt = $this->conexion->prepare("
            SELECT *
            FROM tb_tutorias
            WHERE id_profesor=?
            ORDER BY fecha DESC
        ");

        $stmt->bind_param("i",$id_profesor);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

}