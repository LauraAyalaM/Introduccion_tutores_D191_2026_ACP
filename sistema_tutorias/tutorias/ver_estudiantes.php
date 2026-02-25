<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../models/Tutoria.php';

// Solo profesores
verificarRol('profesor');

$id_profesor = $_SESSION['id_usuario'];
$model = new Tutoria($conexion);

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    header('Location: /Introduccion_tutores_D191_2026_ACP/sistema_tutorias/dashboard/profesor.php');
    exit();
}

// Verificar que la tutoría pertenezca al profesor
$t = $model->getById($id);
if (!$t || $t['id_profesor'] != $id_profesor) {
    header('Location: /Introduccion_tutores_D191_2026_ACP/sistema_tutorias/dashboard/profesor.php');
    exit();
}

$estudiantes = $model->getReservasDetalles($id);

?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<?php include __DIR__ . '/../includes/navbar.php'; ?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">Estudiantes inscritos</h4>
            <small class="text-muted"><?php echo htmlspecialchars($t['tema']); ?> - <?php echo $t['fecha']; ?></small>
        </div>
        <div>
            <a href="/Introduccion_tutores_D191_2026_ACP/sistema_tutorias/dashboard/profesor.php" class="btn btn-secondary">Volver</a>
        </div>
    </div>

    <?php if (count($estudiantes) > 0): ?>
        <div class="table-responsive bg-white rounded-3 shadow-sm p-3">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Estado Reserva</th>
                        <th>Fecha Reserva</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($estudiantes as $e): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($e['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($e['correo']); ?></td>
                            <td><?php echo htmlspecialchars($e['estado']); ?></td>
                            <td><?php echo htmlspecialchars($e['fecha_reserva']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="bg-white p-4 rounded-3 shadow-sm text-center">
            <p class="text-muted mb-0">No hay estudiantes inscritos en esta tutoría.</p>
        </div>
    <?php endif; ?>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
