<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../models/Tutoria.php';

verificarRol('profesor');

$id_profesor = $_SESSION['id_usuario'];
$model = new Tutoria($conexion);
$tutorias = $model->getByProfesor($id_profesor);

?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<?php include __DIR__ . '/../includes/navbar.php'; ?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">Mis Tutorías</h4>
            <small class="text-muted">Listado de tus tutorías</small>
        </div>
        <div>
            <a href="/Introduccion_tutores_D191_2026_ACP/sistema_tutorias/tutorias/crear_profesor.php" class="btn btn-success">+ Nueva Tutoría</a>
        </div>
    </div>

    <?php if (count($tutorias) > 0): ?>
        <div class="row g-4">
            <?php foreach ($tutorias as $t): ?>
                <?php
                    $reservas = $model->countReservas($t['id_tutoria']);
                    $cupos_disponibles = $t['cupos'] - $reservas;
                    $border_class = 'border-primary';
                    if ($t['estado'] == 'reservada') $border_class = 'border-warning';
                    elseif ($t['estado'] == 'cancelada') $border_class = 'border-danger';
                ?>

                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm rounded-3 border-3 <?php echo $border_class; ?>">
                        <div class="card-body d-flex flex-column">
                            <span class="badge bg-<?php echo ($t['estado']=='disponible')?'success':(($t['estado']=='reservada')?'warning':'danger'); ?>">
                                <?php echo ucfirst($t['estado']); ?>
                            </span>
                            <h5 class="fw-semibold mt-2"><?php echo htmlspecialchars($t['tema']); ?></h5>
                            <p class="text-muted mb-1">Fecha: <?php echo $t['fecha']; ?></p>
                            <p class="mb-2">Horario: <?php echo $t['hora_inicio']; ?> - <?php echo $t['hora_fin']; ?></p>
                            <p class="mb-2">Cupos: <?php echo $t['cupos']; ?> &nbsp; <small class="text-muted">(Inscritos: <?php echo $reservas; ?>)</small></p>

                            <div class="mt-auto d-flex gap-2">
                                <a href="/Introduccion_tutores_D191_2026_ACP/sistema_tutorias/tutorias/ver_estudiantes.php?id=<?php echo $t['id_tutoria']; ?>" class="btn btn-outline-primary w-50">Ver Estudiantes</a>
                                <?php if ($t['estado'] != 'cancelada'): ?>
                                    <a href="/Introduccion_tutores_D191_2026_ACP/sistema_tutorias/tutorias/cambiar_estado_profesor.php?id=<?php echo $t['id_tutoria']; ?>&estado=cancelada" class="btn btn-outline-danger w-50" onclick="return confirm('¿Cancelar esta tutoría?');">Cancelar</a>
                                <?php else: ?>
                                    <a href="/Introduccion_tutores_D191_2026_ACP/sistema_tutorias/tutorias/cambiar_estado_profesor.php?id=<?php echo $t['id_tutoria']; ?>&estado=disponible" class="btn btn-outline-success w-50" onclick="return confirm('¿Reabrir esta tutoría?');">Reabrir</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="bg-white p-4 rounded-3 shadow-sm text-center">
            <p class="text-muted mb-0">No tienes tutorías registradas.</p>
        </div>
    <?php endif; ?>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
