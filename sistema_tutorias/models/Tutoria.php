<?php
require_once __DIR__ . '/../config/conexion.php';

class Tutoria {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Obtener tutorías de un profesor
    public function getByProfesor($id_profesor) {
        $stmt = $this->conexion->prepare(
            "SELECT * FROM tb_tutorias WHERE id_profesor = ? ORDER BY fecha DESC"
        );
        $stmt->bind_param("i", $id_profesor);
        $stmt->execute();
        $result = $stmt->get_result();
        $tutorias = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $tutorias;
    }

    // Crear nueva tutoría
    public function create($id_profesor, $tema, $fecha, $hora_inicio, $hora_fin, $cupos) {
        $stmt = $this->conexion->prepare(
            "INSERT INTO tb_tutorias (id_profesor, fecha, hora_inicio, hora_fin, tema, cupos, estado) VALUES (?, ?, ?, ?, ?, ?, 'disponible')"
        );
        $stmt->bind_param("issssi", $id_profesor, $fecha, $hora_inicio, $hora_fin, $tema, $cupos);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // Contar reservas de una tutoría
    public function countReservas($id_tutoria) {
        $stmt = $this->conexion->prepare("SELECT COUNT(*) as total FROM tb_reservas WHERE id_tutoria = ?");
        $stmt->bind_param("i", $id_tutoria);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return (int)$res['total'];
    }

    // Obtener estudiantes inscritos en una tutoría
    public function getReservasDetalles($id_tutoria) {
        $stmt = $this->conexion->prepare(
            "SELECT u.nombre, u.correo, r.estado, r.fecha_reserva
             FROM tb_reservas r
             INNER JOIN tb_usuarios u ON r.id_estudiante = u.id_usuario
             WHERE r.id_tutoria = ? AND u.id_rol = 1"
        );
        $stmt->bind_param("i", $id_tutoria);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    // Obtener tutoría por id
    public function getById($id_tutoria) {
        $stmt = $this->conexion->prepare("SELECT * FROM tb_tutorias WHERE id_tutoria = ?");
        $stmt->bind_param("i", $id_tutoria);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $res;
    }

    // Cambiar estado de una tutoría (con verificación externa de propietario)
    public function cambiarEstado($id_tutoria, $estado) {
        $stmt = $this->conexion->prepare("UPDATE tb_tutorias SET estado = ? WHERE id_tutoria = ?");
        $stmt->bind_param("si", $estado, $id_tutoria);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}

?>
