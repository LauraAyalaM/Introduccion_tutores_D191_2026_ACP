<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../models/Tutoria.php';

// Solo profesores
verificarRol('profesor');

$mensaje = '';
$id_profesor = $_SESSION['id_usuario'];

$model = new Tutoria($conexion);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tema = trim($_POST['tema']);
    $fecha = $_POST['fecha'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fin = $_POST['hora_fin'];
    $cupos = (int)$_POST['cupos'];

    // Validaciones
    $errores = [];
    if (empty($tema)) $errores[] = 'El tema es obligatorio.';
    if (!$fecha || $fecha < date('Y-m-d')) $errores[] = 'La fecha no puede ser pasada.';
    if (!$hora_inicio || !$hora_fin || $hora_fin <= $hora_inicio) $errores[] = 'La hora fin debe ser mayor que la hora inicio.';
    if ($cupos <= 0) $errores[] = 'Los cupos deben ser mayores a 0.';

    if (count($errores) === 0) {
        $ok = $model->create($id_profesor, $tema, $fecha, $hora_inicio, $hora_fin, $cupos);
        if ($ok) {
            $mensaje = 'Tutoría creada correctamente.';
        } else {
            $mensaje = 'Error al crear la tutoría.';
        }
    } else {
        $mensaje = implode('<br>', $errores);
    }
}

?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<?php include __DIR__ . '/../includes/navbar.php'; ?>

<div class="container py-5">
    <h4 class="mb-4">Crear Nueva Tutoría</h4>

    <?php if ($mensaje): ?>
        <div class="alert alert-info"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="POST" class="bg-white p-4 rounded-3 shadow-sm border border-primary">
        <div class="row g-3">
            <div class="col-md-12">
                <label class="form-label">Tema / Materia</label>
                <input type="text" name="tema" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Fecha</label>
                <input type="date" name="fecha" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Hora Inicio</label>
                <input type="time" name="hora_inicio" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Hora Fin</label>
                <input type="time" name="hora_fin" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Cupos</label>
                <input type="number" name="cupos" class="form-control" min="1" required>
            </div>

            <div class="col-12 text-center mt-3">
                <button type="submit" class="btn btn-success w-50">Crear Tutoría</button>
            </div>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
