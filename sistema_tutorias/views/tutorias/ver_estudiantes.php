<?php
require_once "../../includes/auth.php";
require_once "../../config/conexion.php";

$id_tutoria = $_GET['id'] ?? null;

if (!$id_tutoria) {
    header("Location: listar.php");
    exit();
}

$sql = "
SELECT 
u.nombre,
u.correo

FROM tb_reservas r

INNER JOIN tb_usuarios u
ON r.id_estudiante = u.id_usuario

WHERE r.id_tutoria = ?
";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_tutoria);
$stmt->execute();

$resultado = $stmt->get_result();
?>

<?php include "../../includes/header.php"; ?>
<?php include "../../includes/navbar.php"; ?>

<div class="container py-5">

<h4 class="mb-4">Estudiantes inscritos</h4>

<?php if ($resultado->num_rows > 0): ?>

<div class="table-responsive">

<table class="table table-bordered bg-white shadow-sm">

<thead class="table-light">

<tr>
<th>Nombre</th>
<th>Email</th>
</tr>

</thead>

<tbody>

<?php while($est = $resultado->fetch_assoc()): ?>

<tr>

<td>
<?php echo htmlspecialchars($est['nombre']); ?>
</td>

<td>
<?php echo htmlspecialchars($est['correo']); ?>
</td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</div>

<?php else: ?>

<div class="card shadow-sm border-0 text-center">

<div class="card-body py-5">

<h5 class="text-muted mb-2">
No hay estudiantes inscritos
</h5>

<p class="text-muted small mb-0">
Esta tutoría aún no tiene reservas.
</p>

</div>

</div>

<?php endif; ?>

<a href="listar.php" class="btn btn-secondary mt-4">
Volver
</a>

</div>

<?php include "../../includes/footer.php"; ?>