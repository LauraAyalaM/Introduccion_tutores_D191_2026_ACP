<?php
require_once "auth.php";

if (!isset($_SESSION['id_usuario'])) {
    return;
}

$rol = $_SESSION['rol'];
$nombre = $_SESSION['nombre'];
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container d-flex align-items-center">

        <!-- Logo -->
        <a class="navbar-brand fw-semibold" href="#">
            Sistema Tutorías
        </a>

        <!-- Responsive Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Contenido -->
        <div class="collapse navbar-collapse align-items-center" id="navbarNav">

            <!-- Menú Izquierdo -->
            <ul class="navbar-nav me-auto d-flex align-items-center gap-2">

                <?php if ($rol == "estudiante") : ?>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/sistema_tutorias/dashboard/estudiante.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/sistema_tutorias/tutorias/listar.php">Tutorías</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/sistema_tutorias/reservas/mis_reservas.php">Mis Reservas</a>
                    </li>
                <?php endif; ?>

                <?php if ($rol == "profesor") : ?>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/sistema_tutorias/dashboard/profesor.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/sistema_tutorias/tutorias/crear.php">Crear Tutoría</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/sistema_tutorias/tutorias/listar.php">Mis Tutorías</a>
                    </li>
                <?php endif; ?>

                <?php if ($rol == "administrador") : ?>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="../dashboard/administrador.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="../usuarios/listar.php">Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="../tutorias/listar.php">Tutorías</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="../reservas/listar.php">Reservas</a>
                    </li>
                <?php endif; ?>

            </ul>

            <!-- Usuario y Logout -->
            <div class="d-flex align-items-center gap-3 text-white">
                <span class="fw-light">
                    <?php echo $nombre; ?>
                </span>

                <a href="../logout.php" 
                   class="btn btn-light btn-sm">
                    Cerrar sesión
                </a>
            </div>

        </div>
    </div>
</nav>