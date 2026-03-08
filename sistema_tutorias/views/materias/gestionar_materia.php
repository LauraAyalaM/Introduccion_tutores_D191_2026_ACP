<?php
require_once "../../includes/auth.php";
require_once "../../config/conexion.php";

if ($_SESSION['rol'] !== "administrador") {
    header("Location: ../../login.php");
    exit();
}

$mensaje = "";

/* PROFESORES */

$profesores = $conexion->query("
SELECT u.id_usuario, u.nombre
FROM tb_usuarios u
INNER JOIN tb_rol r ON u.id_rol = r.id_rol
WHERE r.nombre = 'profesor'
");

/* MATERIAS */
$materias = $conexion->query("
SELECT 
m.id_materia,
m.nombre AS materia,
m.descripcion,
GROUP_CONCAT(u.nombre SEPARATOR ', ') AS profesores
FROM tb_materias m
LEFT JOIN tb_profesor_materia pm 
    ON m.id_materia = pm.id_materia
LEFT JOIN tb_usuarios u 
    ON pm.id_profesor = u.id_usuario
GROUP BY m.id_materia
");

?><?php include "../../includes/header.php"; ?>
<?php include "../../includes/navbar.php"; ?>

<div class="container py-5">

<?php include "../../includes/alerts.php"; ?>

<!-- ============================= -->
<!-- ENCABEZADO -->
<!-- ============================= -->

<div class="mb-4">
    <h4 class="fw-semibold mb-1">Gestión de Materias</h4>
    <small class="text-muted">Administra las materias y asigna profesores responsables</small>
</div>

<div class="row g-4">

<!-- ============================= -->
<!-- CREAR MATERIA -->
<!-- ============================= -->

<div class="col-lg-4">

<div class="card shadow-sm border-0 rounded-3">

<div class="card-body">

<h6 class="fw-semibold mb-3">
Nueva Materia
</h6>

<form method="POST" action="../../controllers/MateriaController.php?accion=crear">

<div class="mb-3">

<label class="form-label">Nombre</label>

<input 
type="text" 
name="nombre" 
class="form-control"
placeholder="Ej: Programación Web"
required>

</div>

<div class="mb-3">

<label class="form-label">Descripción</label>

<textarea 
name="descripcion"
class="form-control"
rows="3"
placeholder="Descripción corta de la materia"></textarea>

</div>

<button class="btn btn-success w-100">
<i class="bi bi-plus-circle me-1"></i>
Crear Materia
</button>

</form>

</div>
</div>

</div>

<!-- ============================= -->
<!-- LISTADO MATERIAS -->
<!-- ============================= -->

<div class="col-lg-8">

<div class="card shadow-sm border-0 rounded-3">

<div class="card-body">

<h6 class="fw-semibold mb-3">
Materias Registradas
</h6>

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead class="table-light">

<tr class="small text-uppercase">
<th>Materia</th>
<th>Descripción</th>
<th>Profesores</th>
<th style="width:220px;">Asignar</th>
</tr>

</thead>

<tbody>

<?php while($m = $materias->fetch_assoc()): ?>

<tr>

<td class="fw-semibold">
<?php echo htmlspecialchars($m['materia']); ?>
</td>

<td class="text-muted">
<?php echo htmlspecialchars($m['descripcion']); ?>
</td>

<td>

<?php if($m['profesores']): ?>

<span class="badge bg-light text-dark border">
<?php echo htmlspecialchars($m['profesores']); ?>
</span>

<?php else: ?>

<span class="text-muted small">
Sin profesores asignados
</span>

<?php endif; ?>

</td>

<td>

<form method="POST" action="../../controllers/MateriaController.php?accion=asignar">

<input type="hidden" name="id_materia" value="<?php echo $m['id_materia']; ?>">

<div class="input-group input-group-sm">

<select name="id_profesor" class="form-select" required>

<option value="">Profesor...</option>

<?php

$profesores_disponibles = $conexion->query("
SELECT u.id_usuario, u.nombre
FROM tb_usuarios u
INNER JOIN tb_rol r ON u.id_rol = r.id_rol
WHERE r.nombre = 'profesor'
AND NOT EXISTS (
SELECT 1
FROM tb_profesor_materia pm
WHERE pm.id_profesor = u.id_usuario
AND pm.id_materia = ".$m['id_materia']."
)
");

while($p = $profesores_disponibles->fetch_assoc()):
?>

<option value="<?php echo $p['id_usuario']; ?>">
<?php echo htmlspecialchars($p['nombre']); ?>
</option>

<?php endwhile; ?>

</select>

<button class="btn btn-primary">
Asignar
</button>

</div>

</form>

</td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</div>

</div>
</div>

</div>

</div>

</div>

<?php include "../../includes/footer.php"; ?>