<?php
require_once "../../includes/auth.php";
require_once "../../config/conexion.php";

$id_usuario = $_SESSION['id_usuario'];
$rol = $_SESSION['rol'];

/* =========================
   MATERIAS DEL PROFESOR
========================= */

$materias = $conexion->query("
SELECT m.id_materia, m.nombre
FROM tb_materias m
INNER JOIN tb_profesor_materia pm
ON m.id_materia = pm.id_materia
WHERE pm.id_profesor = $id_usuario
");

/* =========================
   FILTROS
========================= */

$estado_filtro = $_GET['estado'] ?? '';
$materia_filtro = $_GET['materia'] ?? '';

$where_clauses = [];
$where_clauses[] = "t.id_profesor = $id_usuario";

if ($estado_filtro != '') {
    $where_clauses[] = "t.estado = '" . $conexion->real_escape_string($estado_filtro) . "'";
}

if ($materia_filtro != '') {
    $where_clauses[] = "t.id_materia = '" . $conexion->real_escape_string($materia_filtro) . "'";
}

$where_sql = "WHERE " . implode(" AND ", $where_clauses);

/* =========================
   CONSULTA TUTORIAS
========================= */

$sql = "
SELECT 
t.id_tutoria,
t.fecha,
t.hora_inicio,
t.hora_fin,
t.cupos,
t.estado,
m.nombre AS materia

FROM tb_tutorias t

INNER JOIN tb_materias m
ON t.id_materia = m.id_materia

$where_sql

ORDER BY t.fecha ASC
";

$resultado = $conexion->query($sql);
?>

<?php include "../../includes/header.php"; ?>
<?php include "../../includes/navbar.php"; ?>

<div class="container py-5">

<?php include "../../includes/alerts.php"; ?>

<!-- ENCABEZADO -->

<div class="d-flex justify-content-between align-items-center mb-4">

<div>
<h4 class="fw-semibold mb-0">Mis Tutorías</h4>
<small class="text-muted">Gestiona tus horarios de tutorías</small>
</div>

<a href="crear.php" class="btn btn-success">
+ Nueva Tutoría
</a>

</div>


<!-- FILTROS -->

<form method="GET" class="mb-4 d-flex gap-2 flex-wrap align-items-center">

<select name="estado" class="form-select w-auto">

<option value="">Todos los estados</option>

<option value="disponible" <?php if($estado_filtro=='disponible') echo 'selected'; ?>>
Disponible
</option>

<option value="reservada" <?php if($estado_filtro=='reservada') echo 'selected'; ?>>
Reservada
</option>

<option value="cancelada" <?php if($estado_filtro=='cancelada') echo 'selected'; ?>>
Cancelada
</option>

<option value="finalizada" <?php if($estado_filtro=='finalizada') echo 'selected'; ?>>
Finalizada
</option>

</select>

<select name="materia" class="form-select w-auto">

<option value="">Todas las materias</option>

<?php while($m = $materias->fetch_assoc()): ?>

<option value="<?php echo $m['id_materia']; ?>"
<?php if($materia_filtro == $m['id_materia']) echo 'selected'; ?>>

<?php echo htmlspecialchars($m['nombre']); ?>

</option>

<?php endwhile; ?>

</select>

<button type="submit" class="btn btn-primary">
Filtrar
</button>

<a href="listar.php" class="btn btn-secondary">
Resetear
</a>

</form>


<?php if ($resultado->num_rows > 0): ?>

<div class="row g-4">

<?php while ($tutoria = $resultado->fetch_assoc()): ?>

<?php

/* =========================
   CONTAR RESERVAS
========================= */

$stmt = $conexion->prepare("
SELECT COUNT(*) as total
FROM tb_reservas
WHERE id_tutoria = ?
");

$stmt->bind_param("i", $tutoria['id_tutoria']);
$stmt->execute();

$reservas = $stmt->get_result()->fetch_assoc()['total'];

$stmt->close();

$cupos_disponibles = $tutoria['cupos'] - $reservas;


/* =========================
   COLOR SEGUN ESTADO
========================= */

$card_color = '#c8f1dc';

if ($tutoria['estado'] == 'reservada') {
    $card_color = '#ffe7a3';
} 
elseif ($tutoria['estado'] == 'cancelada') {
    $card_color = '#f7c6c7';
}

?>

<div class="col-md-6 col-lg-4">

<div class="card h-100 shadow-sm rounded-3 border"
style="background-color: <?php echo $card_color; ?>;">

<div class="card-body d-flex flex-column">

<div class="d-flex justify-content-between align-items-start">

<span class="badge bg-<?php 
echo ($tutoria['estado']=='disponible')?'success':
(($tutoria['estado']=='reservada')?'warning':'danger'); 
?>">

<?php echo ucfirst($tutoria['estado']); ?>

</span>

<small class="text-muted">

<?php echo $tutoria['fecha']; ?>

</small>

</div>

<h6 class="mt-3 mb-2 fw-semibold">

<?php echo htmlspecialchars($tutoria['materia']); ?>

</h6>

<p class="mb-2 text-muted small">

Horario:
<strong>

<?php echo $tutoria['hora_inicio']; ?>

-

<?php echo $tutoria['hora_fin']; ?>

</strong>

</p>

<p class="mb-3">

<strong>Cupos disponibles:</strong>

<span class="badge bg-<?php echo ($cupos_disponibles > 0) ? 'success' : 'danger'; ?>">

<?php echo $cupos_disponibles; ?>

</span>

</p>


<div class="mt-auto">

<a href="ver_estudiantes.php?id=<?php echo $tutoria['id_tutoria']; ?>"
class="btn btn-primary w-100 mb-2">
Ver estudiantes
</a>

<div class="d-flex gap-2">

<a href="editar.php?id=<?php echo $tutoria['id_tutoria']; ?>"
class="btn btn-warning w-50">
Editar
</a>

<?php if ($tutoria['estado'] != 'cancelada'): ?>

<a href="../../controllers/TutoriaController.php?accion=estado&id=<?php echo $tutoria['id_tutoria']; ?>&estado=cancelada"
class="btn btn-danger w-50"
onclick="return confirm('¿Seguro que deseas cancelar esta tutoría?')">
Cancelar
</a>

<?php else: ?>

<a href="../../controllers/TutoriaController.php?accion=estado&id=<?php echo $tutoria['id_tutoria']; ?>&estado=disponible"
class="btn btn-success w-50"
onclick="return confirm('¿Deseas reabrir esta tutoría?')">
Reabrir
</a>

<?php endif; ?>

</div>

</div>

</div>

</div>

</div>

<?php endwhile; ?>

</div>

<?php else: ?>

<div class="bg-white p-4 rounded-3 shadow-sm text-center">

<p class="text-muted mb-0">

No tienes tutorías registradas.

</p>

</div>

<?php endif; ?>

</div>

<?php include "../../includes/footer.php"; ?>