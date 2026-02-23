<?php
session_start();

if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['rol'])) {
    header("Location: login.php");
    exit();
}

$rol = strtolower($_SESSION['rol']);
$nombre = htmlspecialchars($_SESSION['nombre']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Dashboard Tutorías</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
.card-hover{
    transition:0.3s;
}

.card-hover:hover{
    transform: scale(1.05);
}
</style>

</head>

<body class="bg-light">

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-primary shadow sticky-top">
<div class="container-fluid">

<span class="navbar-brand fw-bold">
🎓 Sistema de Gestión de Tutorías
</span>

<div class="text-white">

<?= $nombre ?> |
<span class="text-warning"><?= ucfirst($rol) ?></span>

<a href="../logout.php" class="btn btn-light btn-sm ms-3">
Salir
</a>

</div>

</div>
</nav>

<div class="container-fluid mt-5">

<h3 class="mb-4 text-center">
Panel de Control
</h3>

<div class="row g-4 justify-content-center">

<!-- ESTUDIANTE -->
<?php if($rol === "estudiante"): ?>

<div class="col-md-4">
<div class="card shadow card-hover text-center p-4">

<i class="bi bi-search fs-1 text-primary"></i>

<h5 class="mt-3">Tutorías Disponibles</h5>

<p>Consulta y reserva tutorías.</p>

<a href="estudiante/tutorias.php" class="btn btn-primary">
Ver Tutorías
</a>

</div>
</div>


<div class="col-md-4">
<div class="card shadow card-hover text-center p-4">

<i class="bi bi-calendar-check fs-1 text-success"></i>

<h5 class="mt-3">Mis Reservas</h5>

<p>Historial de tutorías reservadas.</p>

<a href="estudiante/reservas.php" class="btn btn-success">
Ver Reservas
</a>

</div>
</div>

<?php endif; ?>


<!-- PROFESOR -->
<?php if($rol === "profesor"): ?>

<div class="col-md-4">
<div class="card shadow card-hover text-center p-4">

<i class="bi bi-plus-circle fs-1 text-primary"></i>

<h5 class="mt-3">Crear Tutoría</h5>

<p>Programar nueva tutoría.</p>

<a href="profesor/crear_tutoria.php" class="btn btn-primary">
Crear Tutoría
</a>

</div>
</div>


<div class="col-md-4">
<div class="card shadow card-hover text-center p-4">

<i class="bi bi-list-ul fs-1 text-secondary"></i>

<h5 class="mt-3">Mis Tutorías</h5>

<p>Gestionar tutorías creadas.</p>

<a href="profesor/mis_tutorias.php" class="btn btn-secondary">
Mis Tutorías
</a>

</div>
</div>

<?php endif; ?>


<!-- ADMINISTRADOR -->
<?php if($rol === "administrador"): ?>

<div class="col-md-4">
<div class="card shadow card-hover text-center p-4">

<i class="bi bi-people fs-1 text-dark"></i>

<h5 class="mt-3">Gestión Usuarios</h5>

<p>Crear, modificar o inactivar usuarios.</p>

<a href="admin/usuarios.php" class="btn btn-dark">
Administrar
</a>

</div>
</div>


<div class="col-md-4">
<div class="card shadow card-hover text-center p-4">

<i class="bi bi-calendar3 fs-1 text-primary"></i>

<h5 class="mt-3">Supervisión Tutorías</h5>

<p>Ver todas las tutorías del sistema.</p>

<a href="admin/tutorias.php" class="btn btn-primary">
Ver Tutorías
</a>

</div>
</div>

<?php endif; ?>

</div>
</div>

</body>
</html>