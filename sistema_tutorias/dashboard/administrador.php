<?php
require_once "../includes/auth.php";
require_once "../config/conexion.php";

// Solo administrador
if ($_SESSION['rol'] !== "administrador") {
    header("Location: ../login.php");
    exit();
}

// Métricas
$total_usuarios = $conexion->query("SELECT COUNT(*) as total FROM tb_usuarios")->fetch_assoc()['total'];
$total_profesores = $conexion->query("
    SELECT COUNT(*) as total 
    FROM tb_usuarios u 
    INNER JOIN tb_rol r ON u.id_rol = r.id_rol 
    WHERE r.nombre = 'profesor'
")->fetch_assoc()['total'];
$total_tutorias = $conexion->query("SELECT COUNT(*) as total FROM tb_tutorias")->fetch_assoc()['total'];
$total_reservas = $conexion->query("SELECT COUNT(*) as total FROM tb_reservas")->fetch_assoc()['total'];

// Últimas tutorías
$ultimas_tutorias = $conexion->query("
    SELECT t.id_tutoria, t.tema, t.fecha, t.hora_inicio, t.hora_fin, t.cupos, t.estado,
           u.nombre as profesor
    FROM tb_tutorias t
    INNER JOIN tb_usuarios u ON t.id_profesor = u.id_usuario
    ORDER BY t.id_tutoria DESC
    LIMIT 5
");
?>

<?php include "../includes/header.php"; ?>
<?php include "../includes/navbar.php"; ?>

<div class="container py-5">

    <!-- ACCIONES RÁPIDAS -->
    <div class="mb-5">
        <h6 class="text-uppercase text-muted mb-3">Acciones rápidas</h6>
        <div class="d-flex gap-3">
            <a href="../usuarios/crear.php" class="btn btn-primary">
                + Crear Usuario
            </a>
            <a href="../tutorias/crear.php" class="btn btn-outline-primary">
                + Crear Tutoría
            </a>
        </div>
    </div>

    <!-- MÉTRICAS -->
    <div class="row g-4 mb-5">

        <div class="col-md-3">
            <div class="p-4 rounded-4 shadow-sm border-start border-4 border-primary bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold text-primary"><?php echo $total_usuarios; ?></h3>
                        <p class="text-muted mb-0">Usuarios</p>
                    </div>
                    <div class="fs-1 text-primary opacity-25">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="p-4 rounded-4 shadow-sm border-start border-4 border-success bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold text-success"><?php echo $total_profesores; ?></h3>
                        <p class="text-muted mb-0">Profesores</p>
                    </div>
                    <div class="fs-1 text-success opacity-25">
                        <i class="bi bi-person-badge"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="p-4 rounded-4 shadow-sm border-start border-4 border-warning bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold text-warning"><?php echo $total_tutorias; ?></h3>
                        <p class="text-muted mb-0">Tutorías</p>
                    </div>
                    <div class="fs-1 text-warning opacity-25">
                        <i class="bi bi-book"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="p-4 rounded-4 shadow-sm border-start border-4 border-danger bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold text-danger"><?php echo $total_reservas; ?></h3>
                        <p class="text-muted mb-0">Reservas</p>
                    </div>
                    <div class="fs-1 text-danger opacity-25">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ÚLTIMAS TUTORÍAS -->
    <div class="bg-white p-4 rounded-3 shadow-sm border border-1">
        <h6 class="mb-3 fw-semibold">Últimas Tutorías</h6>

        <?php if ($ultimas_tutorias->num_rows > 0): ?>
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="small text-uppercase">
                        <th>Tema</th>
                        <th>Fecha</th>
                        <th>Horario</th>
                        <th>Cupos</th>
                        <th>Estado</th>
                        <th>Profesor</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($t = $ultimas_tutorias->fetch_assoc()): ?>
                        <tr>
                            <td class="fw-semibold"><?php echo htmlspecialchars($t['tema']); ?></td>
                            <td><?php echo $t['fecha']; ?></td>
                            <td><?php echo $t['hora_inicio']; ?> - <?php echo $t['hora_fin']; ?></td>
                            <td><?php echo $t['cupos']; ?></td>
                            <td>
                                <?php
                                if ($t['estado'] == 'disponible') echo '<span class="badge bg-success">Disponible</span>';
                                elseif ($t['estado'] == 'reservada') echo '<span class="badge bg-warning text-dark">Reservada</span>';
                                else echo '<span class="badge bg-danger">Cancelada</span>';
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($t['profesor']); ?></td>
                            <td>
                                <a href="../tutorias/editar.php?id=<?php echo $t['id_tutoria']; ?>" class="btn btn-sm btn-warning">Editar</a>

                                <?php if ($t['estado'] != 'cancelada'): ?>
                                    <a href="../tutorias/cambiar_estado.php?id=<?php echo $t['id_tutoria']; ?>&estado=cancelada" 
                                    class="btn btn-sm btn-danger" 
                                    onclick="return confirm('¿Seguro que deseas cancelar esta tutoría?');">
                                    Cancelar
                                    </a>
                                <?php else: ?>
                                    <a href="../tutorias/cambiar_estado.php?id=<?php echo $t['id_tutoria']; ?>&estado=disponible" 
                                    class="btn btn-sm btn-success" 
                                    onclick="return confirm('¿Deseas reabrir esta tutoría?');">
                                    Reabrir
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted mb-0">No hay tutorías registradas.</p>
        <?php endif; ?>
    </div>

</div>

<?php include "../includes/footer.php"; ?>