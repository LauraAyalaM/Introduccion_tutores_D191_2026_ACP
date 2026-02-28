<?php
session_start();
require_once("../config/conexion.php");

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit();
}

$id_estudiante = $_SESSION['id_usuario'];

$sql = "
SELECT r.id_reserva,
       r.estado,
       r.fecha_reserva,
       t.tema,
       t.fecha,
       t.hora_inicio,
       t.hora_fin,
       u.nombre AS profesor
FROM tb_reservas r
INNER JOIN tb_tutorias t ON r.id_tutoria = t.id_tutoria
INNER JOIN tb_usuarios u ON t.id_profesor = u.id_usuario
WHERE r.id_estudiante = ?
ORDER BY r.fecha_reserva DESC
";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_estudiante);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Reservas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">📚 Mis Reservas</h4>
        </div>

        <div class="card-body">
            <table class="table table-hover table-bordered text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Tema</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Profesor</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>

                <tbody>
                <?php while ($row = $resultado->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['tema']; ?></td>
                        <td><?= $row['fecha']; ?></td>
                        <td><?= $row['hora_inicio'] . " - " . $row['hora_fin']; ?></td>
                        <td><?= $row['profesor']; ?></td>
                        <td>
                            <?php if ($row['estado'] == 'activa') { ?>
                                <span class="badge bg-success">Activa</span>
                            <?php } else { ?>
                                <span class="badge bg-danger">Cancelada</span>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if ($row['estado'] == 'activa') { ?>
                                <a href="cancelar.php?id=<?= $row['id_reserva']; ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('¿Seguro que deseas cancelar esta reserva?')">
                                   Cancelar
                                </a>
                            <?php } else { ?>
                                ---
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>

            </table>
        </div>
    </div>
</div>

</body>
</html>
