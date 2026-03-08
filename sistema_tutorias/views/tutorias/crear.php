<?php
require_once "../../includes/auth.php";
require_once "../../config/conexion.php";

// Solo administrador
if ($_SESSION['rol'] !== "profesor") {
    header("Location: ../login.php");
    exit();
}

$id_profesor = $_SESSION['id_usuario'];

$materias = $conexion->query("
SELECT m.id_materia, m.nombre
FROM tb_materias m
INNER JOIN tb_profesor_materia pm
ON m.id_materia = pm.id_materia
WHERE pm.id_profesor = $id_profesor
");

$mensaje = '';

?>
<?php include "../../includes/header.php"; ?>
<?php include "../../includes/navbar.php"; ?>

<div class="container py-5">

<?php include "../../includes/alerts.php"; ?>

<div class="row justify-content-center">

<div class="col-lg-8">

<!-- CARD PRINCIPAL -->
<div class="card shadow-sm border-0">

<div class="card-header bg-white">

<h5 class="mb-0 fw-semibold">
Crear Nueva Tutoría
</h5>

<small class="text-muted">
Define un nuevo horario disponible para tus estudiantes
</small>

</div>

<div class="card-body">

<form method="POST" action="../../controllers/TutoriaController.php?accion=crear">

<div class="row g-4">

<!-- MATERIA -->
<div class="col-md-12">

<label class="form-label fw-semibold">
Materia
</label>

<select name="id_materia" class="form-select" required>

<option value="">Seleccionar materia</option>

<?php while($m = $materias->fetch_assoc()): ?>

<option value="<?php echo $m['id_materia']; ?>">
<?php echo htmlspecialchars($m['nombre']); ?>
</option>

<?php endwhile; ?>

</select>

<small class="text-muted">
Materia a la que pertenece la tutoría
</small>

</div>


<!-- FECHA -->
<div class="col-md-4">

<label class="form-label fw-semibold">
Fecha
</label>

<input 
type="date" 
name="fecha" 
class="form-control"
required>

</div>


<!-- HORA INICIO -->
<div class="col-md-4">

<label class="form-label fw-semibold">
Hora Inicio
</label>

<input 
type="time" 
name="hora_inicio" 
class="form-control"
required>

</div>


<!-- HORA FIN -->
<div class="col-md-4">

<label class="form-label fw-semibold">
Hora Fin
</label>

<input 
type="time" 
name="hora_fin" 
class="form-control"
required>

</div>


<!-- CUPOS -->
<div class="col-md-4">

<label class="form-label fw-semibold">
Cupos disponibles
</label>

<input 
type="number" 
name="cupos"
class="form-control"
min="1"
placeholder="Ej: 5"
required>

<small class="text-muted">
Número máximo de estudiantes
</small>

</div>

</div>


<!-- BOTÓN -->
<div class="text-center mt-4">

<button type="submit" class="btn btn-success px-5">

Crear Tutoría

</button>

</div>

</form>

</div>

</div>

<!-- CARD DE INFORMACIÓN -->

<div class="card border-0 shadow-sm mt-4">

<div class="card-body">

<h6 class="fw-semibold mb-3">
Recomendaciones
</h6>

<ul class="text-muted small mb-0">

<li>Evita crear tutorías en horarios que ya estén ocupados.</li>

<li>Define horarios realistas para atender estudiantes.</li>

<li>Recuerda que los estudiantes podrán reservar los cupos disponibles.</li>

</ul>

</div>

</div>

</div>

</div>

</div>

<?php include "../../includes/footer.php"; ?>