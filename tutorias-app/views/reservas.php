<?php
session_start();
include "../config/db.php";
include "../includes/header.php";

// Verificar sesión
if(!isset($_SESSION['usuario'])){
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['usuario']['id_usuario'];

// Obtener reservas del estudiante
$sql = "
SELECT r.*, 
       t.fecha,
       t.hora_inicio,
       t.hora_fin,
       t.tema,
       u.nombre AS profesor
FROM tb_reservas r
INNER JOIN tb_tutorias t ON r.id_tutoria = t.id_tutoria
INNER JOIN tb_usuarios u ON t.id_profesor = u.id_usuario
WHERE r.id_estudiante = $id_usuario
ORDER BY r.fecha_reserva DESC
";

$result = $conn->query($sql);
?>

<h3 class="mb-4">📌 Mis reservas</h3>

<div class="table-responsive">

<table class="table table-bordered table-hover bg-white">

<thead class="table-dark">
<tr>
    <th>Fecha Tutoría</th>
    <th>Hora</th>
    <th>Profesor</th>
    <th>Tema</th>
    <th>Estado</th>
    <th>Acción</th>
</tr>
</thead>

<tbody>

<?php while($row = $result->fetch_assoc()): ?>

<tr>

<td><?= $row['fecha'] ?></td>

<td>
<?= $row['hora_inicio'] ?> -
<?= $row['hora_fin'] ?>
</td>

<td><?= $row['profesor'] ?></td>

<td><?= $row['tema'] ?></td>

<td>

<?php
$estado = $row['estado'];

if($estado == "activa"){
    echo '<span class="badge bg-success">Activa</span>';
}
elseif($estado == "cancelada"){
    echo '<span class="badge bg-danger">Cancelada</span>';
}
else{
    echo '<span class="badge bg-primary">Asistida</span>';
}
?>

</td>

<td>

<?php if($estado == "activa"): ?>

<a href="../controllers/cancelar_reserva.php?id_reserva=<?= $row['id_reserva'] ?>"
   class="btn btn-sm btn-danger"
   onclick="return confirm('¿Cancelar reserva?')">
   Cancelar
</a>

<?php else: ?>

<button class="btn btn-sm btn-secondary" disabled>
No disponible
</button>

<?php endif; ?>

</td>

</tr>

<?php endwhile; ?>

</tbody>
</table>

</div>

<?php include "../includes/footer.php"; ?>