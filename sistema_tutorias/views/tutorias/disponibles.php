<?php
require_once "../../includes/auth.php";
require_once "../../config/conexion.php";

if ($_SESSION['rol'] !== "estudiante") {
    header("Location: ../login.php");
    exit();
}

$id_estudiante = $_SESSION['id_usuario'];
$nombre = $_SESSION['nombre'];


/* =========================
   FILTROS
========================= */

$estado_filtro = $_GET['estado'] ?? '';
$materia_filtro = $_GET['materia'] ?? '';

$where = [];
$where[] = "t.estado IN ('disponible','reservada')";

if ($estado_filtro != '') {
    $where[] = "t.estado = '".$conexion->real_escape_string($estado_filtro)."'";
}

if ($materia_filtro != '') {
    $where[] = "t.id_materia = '".$conexion->real_escape_string($materia_filtro)."'";
}

$where_sql = "WHERE ".implode(" AND ", $where);


/* =========================
   MATERIAS PARA FILTRO
========================= */

$materias = $conexion->query("
SELECT id_materia, nombre
FROM tb_materias
ORDER BY nombre ASC
");


/* =========================
   RESERVAR TUTORIA
========================= */

if (isset($_GET['reservar'])) {

    $id_tutoria = intval($_GET['reservar']);

    $consulta = $conexion->query("
        SELECT cupos, estado
        FROM tb_tutorias
        WHERE id_tutoria = $id_tutoria
    ");

    if ($consulta->num_rows > 0) {

        $datos = $consulta->fetch_assoc();
        $cupos_totales = $datos['cupos'];
        $estado = $datos['estado'];

        if ($estado === 'disponible') {

            $conteo = $conexion->query("
                SELECT COUNT(*) as inscritos
                FROM tb_reservas
                WHERE id_tutoria = $id_tutoria
            ");

            $inscritos = $conteo->fetch_assoc()['inscritos'];

            if ($inscritos < $cupos_totales) {

                $check = $conexion->query("
                    SELECT *
                    FROM tb_reservas
                    WHERE id_tutoria = $id_tutoria
                    AND id_estudiante = $id_estudiante
                ");

                if ($check->num_rows == 0) {

                    $conexion->query("
                        INSERT INTO tb_reservas (id_tutoria, id_estudiante)
                        VALUES ($id_tutoria, $id_estudiante)
                    ");

                    $conteo2 = $conexion->query("
                        SELECT COUNT(*) as inscritos
                        FROM tb_reservas
                        WHERE id_tutoria = $id_tutoria
                    ");

                    $inscritos_actualizados = $conteo2->fetch_assoc()['inscritos'];

                    if ($inscritos_actualizados >= $cupos_totales) {

                        $conexion->query("
                            UPDATE tb_tutorias
                            SET estado='reservada'
                            WHERE id_tutoria=$id_tutoria
                        ");
                    }
                }
            }
        }
    }

    header("Location: disponibles.php");
    exit();
}


/* =========================
   CONSULTA TUTORIAS
========================= */

$tutorias = $conexion->query("
SELECT 
t.*,
u.nombre AS profesor,
m.nombre AS materia,

(t.cupos - (
    SELECT COUNT(*)
    FROM tb_reservas r
    WHERE r.id_tutoria = t.id_tutoria
)) AS cupos_disponibles,

EXISTS(
    SELECT 1
    FROM tb_reservas r
    WHERE r.id_tutoria = t.id_tutoria
    AND r.id_estudiante = $id_estudiante
) AS ya_reservada

FROM tb_tutorias t

INNER JOIN tb_usuarios u
ON t.id_profesor = u.id_usuario

INNER JOIN tb_materias m
ON t.id_materia = m.id_materia

$where_sql

ORDER BY t.fecha ASC
");

?>

<?php include "../../includes/header.php"; ?>
<?php include "../../includes/navbar.php"; ?>

<div class="container py-5">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>
<h4 class="fw-semibold mb-0">Panel Estudiante</h4>
<small class="text-muted">Bienvenido, <?php echo htmlspecialchars($nombre); ?></small>
</div>

<a href="../reservas/mis_reservas.php" class="btn btn-primary">
Mis reservas
</a>

</div>


<!-- FILTROS -->

<form method="GET" class="mb-4 d-flex gap-2 flex-wrap align-items-center">

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

<a href="disponibles.php" class="btn btn-secondary">
Resetear
</a>

</form>



<!-- LISTA DE TUTORIAS -->

<div class="row g-4">

<?php while ($t = $tutorias->fetch_assoc()): ?>

<?php

/* COLOR SEGUN ESTADO */

$card_color = '#c8f1dc'; // disponible

if ($t['ya_reservada']) {
    $card_color = '#fff3cd'; // reservada por el estudiante
}
elseif ($t['estado'] == 'reservada') {
    $card_color = '#ffe7a3'; // llena
}

?>

<div class="col-md-6 col-lg-4">

<div class="card h-100 shadow-sm rounded-3 border"
style="background-color: <?php echo $card_color; ?>;">

<div class="card-body d-flex flex-column">

<div class="d-flex justify-content-between">

<span class="badge bg-<?php
echo ($t['estado']=='disponible')?'success':'warning text-dark';
?>">

<?php echo ucfirst($t['estado']); ?>

</span>

<small class="text-muted">

<?php echo $t['fecha']; ?>

</small>

</div>


<h6 class="fw-semibold mt-3">

<?php echo htmlspecialchars($t['materia']); ?>

</h6>

<p class="text-muted mb-1 small">

Profesor: <?php echo htmlspecialchars($t['profesor']); ?>

</p>

<p class="mb-2 small">

Horario:
<strong>

<?php echo $t['hora_inicio']; ?> - <?php echo $t['hora_fin']; ?>

</strong>

</p>

<p class="mb-3">

Cupos disponibles:

<span class="badge bg-<?php echo ($t['cupos_disponibles']>0)?'success':'danger'; ?>">

<?php echo $t['cupos_disponibles']; ?>

</span>

</p>


<div class="mt-auto">

<?php if ($t['ya_reservada']): ?>

<button class="btn btn-warning w-100" disabled>
Ya reservaste esta tutoría
</button>

<?php elseif ($t['estado']=='disponible' && $t['cupos_disponibles']>0): ?>

<a href="?reservar=<?php echo $t['id_tutoria']; ?>"
class="btn btn-success w-100"
onclick="return confirm('¿Deseas reservar esta tutoría?');">

Reservar

</a>

<?php else: ?>

<button class="btn btn-secondary w-100" disabled>
Sin cupos disponibles
</button>

<?php endif; ?>

</div>

</div>

</div>

</div>

<?php endwhile; ?>

</div>

</div>

<?php include "../../includes/footer.php"; ?>