<?php
require_once "auth.php";
require_once __DIR__ . "/../config/app.php";

if (!isset($_SESSION['id_usuario'])) {
    return;
}

$rol = $_SESSION['rol'];
$nombre = $_SESSION['nombre'];
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container d-flex align-items-center">

        <!-- Logo -->
        <a class="navbar-brand fw-semibold">
            Sistema Tutorías
        </a>

        <!-- Responsive Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Contenido -->
        <div class="collapse navbar-collapse align-items-center" id="navbarNav">

            <ul class="navbar-nav me-auto d-flex align-items-center gap-2">

                <!-- ESTUDIANTE -->
               <!--  <?php if ($rol == "estudiante") : ?>

                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= BASE_URL ?>dashboard/estudiante.php">
                        Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= BASE_URL ?>views/tutorias/listar.php">
                        Tutorías Disponibles
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= BASE_URL ?>views/reservas/mis_reservas.php">
                        Mis Reservas
                        </a>
                    </li>

                <?php endif; ?> -->

                <!-- PROFESOR -->
                <!-- <?php if ($rol == "profesor") : ?>

                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= BASE_URL ?>views/tutorias/listar.php">
                        Mis Tutorías
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= BASE_URL ?>views/tutorias/crear.php">
                        Crear Tutoría
                        </a>
                    </li>

                    

                <?php endif; ?> -->

                <!-- ADMIN -->
                <?php if ($rol == "administrador") : ?>

                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= BASE_URL ?>dashboard/administrador.php">
                        Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= BASE_URL ?>views/usuarios/listar.php">
                        Usuarios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= BASE_URL ?>views/materias/gestionar_materia.php">
                          Gestionar Materias
                        </a>
                    </li>

                    <!-- <li class="nav-item">
                        <a class="nav-link text-white" href="<?= BASE_URL ?>views/tutorias/listar.php">
                        Tutorías
                        </a>
                    </li> -->

                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= BASE_URL ?>views/reservas/listar.php">
                        Reservas
                        </a>
                    </li>

                <?php endif; ?>

            </ul>

            <!-- Usuario -->
            <div class="d-flex align-items-center gap-3 text-white">

                <span class="fw-light">
                    <?= htmlspecialchars($nombre) ?>
                </span>

                <a href="<?= BASE_URL ?>logout.php" class="btn btn-light btn-sm">
                    Cerrar sesión
                </a>

            </div>

        </div>
    </div>
</nav>