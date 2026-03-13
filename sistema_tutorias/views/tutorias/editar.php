<?php
require_once "../../includes/auth.php";
require_once "../../config/conexion.php";

if ($_SESSION['rol'] == "estudiante" ) {
    header("Location: ../../login.php");
    exit();
}

$id_tutoria = $_GET['id'] ?? null;

if (!$id_tutoria) {
    header("Location: listar.php");
    exit();
}

/* =========================
   OBTENER TUTORIA
========================= */

$stmt = $conexion->prepare("
SELECT 
t.*,
m.nombre AS materia
FROM tb_tutorias t
INNER JOIN tb_materias m
ON t.id_materia = m.id_materia
WHERE t.id_tutoria = ?
");

$stmt->bind_param("i", $id_tutoria);
$stmt->execute();

$result = $stmt->get_result();
$tutoria = $result->fetch_assoc();

$stmt->close();

if (!$tutoria) {
    header("Location: listar.php");
    exit();
}
?>

<?php include "../../includes/header.php"; ?>
<?php include "../../includes/navbar.php"; ?>

<div class="container py-5">

<?php include "../../includes/alerts.php"; ?>

<div class="row justify-content-center">

<div class="col-lg-8">

<!-- CARD -->

<div class="card shadow-sm border-0">

<div class="card-header bg-white">

<h5 class="mb-0 fw-semibold">
Editar Tutoría
</h5>

<small class="text-muted">
Actualiza la fecha, horario o cupos de esta tutoría
</small>

</div>

<div class="card-body">

<!-- INFORMACIÓN DE LA TUTORÍA -->

<div class="mb-4">

<p class="mb-1">
<strong>Materia:</strong>
<?php echo htmlspecialchars($tutoria['materia']); ?>
</p>

<p class="mb-0 text-muted small">
ID Tutoría: <?php echo $tutoria['id_tutoria']; ?>
</p>

</div>


<form method="POST" action="../../controllers/TutoriaController.php?accion=actualizar">

<input type="hidden" name="id_tutoria" value="<?php echo $tutoria['id_tutoria']; ?>">

<div class="row g-3">

<!-- FECHA -->

<div class="col-md-4">

<label class="form-label">Fecha</label>

<input
type="date"
name="fecha"
class="form-control"
required
value="<?php echo $tutoria['fecha']; ?>">

</div>

<!-- HORA INICIO -->

<div class="col-md-4">

<label class="form-label">Hora Inicio</label>

<input
type="time"
name="hora_inicio"
class="form-control"
required
value="<?php echo $tutoria['hora_inicio']; ?>">

</div>

<!-- HORA FIN -->

<div class="col-md-4">

<label class="form-label">Hora Fin</label>

<input
type="time"
name="hora_fin"
class="form-control"
required
value="<?php echo $tutoria['hora_fin']; ?>">

</div>

<!-- CUPOS -->

<div class="col-md-4">

<label class="form-label">Cupos disponibles</label>

<input
type="number"
name="cupos"
class="form-control"
min="1"
required
value="<?php echo $tutoria['cupos']; ?>">

</div>

</div>

<!-- BOTONES -->

<div class="d-flex justify-content-between mt-4">

<a href="<?php 
    echo ($_SESSION['rol'] === 'administrador') 
        ? '../../dashboard/administrador.php' 
        : 'listar.php'; 
?>" class="btn btn-secondary">
Volver
</a>

<button type="submit" class="btn btn-warning">
Actualizar Tutoría
</button>

</div>

</form>

</div>

</div>

</div>

</div>

</div>

<?php include "../../includes/footer.php"; ?>