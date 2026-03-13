<?php
session_start();
require_once("../../config/conexion.php");

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit();
}

$id_estudiante = $_SESSION['id_usuario'];

$buscar = $_GET['buscar'] ?? '';

$sql = "
SELECT r.id_reserva,
       r.estado,
       r.fecha_reserva,
       t.fecha,
       t.hora_inicio,
       t.hora_fin,
       m.nombre AS materia,
       u.nombre AS profesor
FROM tb_reservas r
INNER JOIN tb_tutorias t ON r.id_tutoria = t.id_tutoria
INNER JOIN tb_usuarios u ON t.id_profesor = u.id_usuario
INNER JOIN tb_materias m ON t.id_materia = m.id_materia
WHERE r.id_estudiante = ?
";

if ($buscar != '') {
    $sql .= " AND (u.nombre LIKE ? OR m.nombre LIKE ? OR t.fecha LIKE ?)";
}

$sql .= " ORDER BY r.fecha_reserva DESC";

$stmt = $conexion->prepare($sql);

if ($buscar != '') {
    $param = "%$buscar%";
    $stmt->bind_param("isss", $id_estudiante, $param, $param, $param);
} else {
    $stmt->bind_param("i", $id_estudiante);
}

$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<title>Mis Reservas</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

</head>

<body class="bg-light">

<?php include "../../includes/header.php"; ?>
<?php include "../../includes/navbar.php"; ?>

<div class="container mt-5">

<div class="card shadow-lg">

<div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

<h4 class="mb-0">Mis Reservas</h4>

<a href="../tutorias/disponibles.php" class="btn btn-light btn-sm">
+ Reservar tutoría
</a>

</div>

<div class="card-body">

<div class="table-responsive">

<table id="tablaReservas" class="table table-striped table-bordered align-middle">

<thead class="table-dark">

<tr>
<th>Materia</th>
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

<td><?= htmlspecialchars($row['materia']); ?></td>

<td><?= htmlspecialchars($row['fecha']); ?></td>

<td><?= $row['hora_inicio'] . " - " . $row['hora_fin']; ?></td>

<td><?= htmlspecialchars($row['profesor']); ?></td>

<td>

<?php if ($row['estado'] == 'activa') { ?>

<span class="badge bg-success">Activa</span>

<?php } else { ?>

<span class="badge bg-danger">Cancelada</span>

<?php } ?>

</td>

<td>

<?php if ($row['estado'] == 'activa') { ?>

<a href="../../controllers/ReservaController.php?accion=cancelar&id=<?= $row['id_reserva']; ?>" 
   class="btn btn-danger btn-sm"
   onclick="return confirm('¿Seguro que deseas cancelar esta reserva?')">
Cancelar
</a>
<?php } else { ?>

<span class="text-muted">---</span>

<?php } ?>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

<script>

$(document).ready(function(){

$('#tablaReservas').DataTable({

responsive: true,

pageLength: 10,

order: [[1, "desc"]],

language: {
lengthMenu: "Mostrar _MENU_ registros",
zeroRecords: "No se encontraron resultados",
info: "Mostrando _START_ a _END_ de _TOTAL_ reservas",
infoEmpty: "No hay reservas",
infoFiltered: "(filtrado de _MAX_ registros)",
search: "Buscar:",
paginate: {
first: "Primero",
last: "Último",
next: "Siguiente",
previous: "Anterior"
}
}

});

});

</script>

</body>
</html>