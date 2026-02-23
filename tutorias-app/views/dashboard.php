<?php
session_start();
include "../config/db.php";
include "../includes/header.php";

// Verificar login
if(!isset($_SESSION['usuario'])){
    header("Location: login.php");
    exit;
}

$usuario = $_SESSION['usuario'];
$id_usuario = $usuario['id_usuario'];
$id_rol = $usuario['id_rol'];

/*
===============================
ESTADÍSTICAS DEL SISTEMA
===============================
*/

// Tutorías disponibles
$tutorias_disponibles = $conn->query("
    SELECT COUNT(*) as total
    FROM tb_tutorias
    WHERE estado='disponible'
")->fetch_assoc()['total'];

// Reservas activas del estudiante
$reservas_activas = $conn->query("
    SELECT COUNT(*) as total
    FROM tb_reservas
    WHERE id_estudiante=$id_usuario
    AND estado='activa'
")->fetch_assoc()['total'];

// Tutorías creadas por profesor (si aplica)
$tutorias_profesor = 0;

if($id_rol == 2){ // 2 = profesor (puedes cambiarlo)
    $tutorias_profesor = $conn->query("
        SELECT COUNT(*) as total
        FROM tb_tutorias
        WHERE id_profesor=$id_usuario
    ")->fetch_assoc()['total'];
}
?>

<h3 class="mb-4">📊 Dashboard</h3>

<div class="row">

<!-- Tutorías disponibles -->
<div class="col-md-4 mb-3">
<div class="card shadow-sm border-0">
<div class="card-body text-center">

<h5>📚 Tutorías disponibles</h5>
<h2><?= $tutorias_disponibles ?></h2>

</div>
</div>
</div>

<!-- Reservas activas -->
<div class="col-md-4 mb-3">
<div class="card shadow-sm border-0">
<div class="card-body text-center">

<h5>✅ Mis reservas activas</h5>
<h2><?= $reservas_activas ?></h2>

</div>
</div>
</div>

<!-- Tutorías del profesor -->
<?php if($id_rol == 2): ?>

<div class="col-md-4 mb-3">
<div class="card shadow-sm border-0">
<div class="card-body text-center">

<h5>👨‍🏫 Mis tutorías</h5>
<h2><?= $tutorias_profesor ?></h2>

</div>
</div>
</div>

<?php endif; ?>

</div>

<hr>

<p class="text-muted">
Bienvenido al sistema de agendamiento de tutorías.
</p>

<?php include "../includes/footer.php"; ?>