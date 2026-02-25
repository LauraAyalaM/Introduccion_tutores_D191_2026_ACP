<?php
require_once "../includes/auth.php";
require_once "../config/conexion.php";

// Solo administrador
if ($_SESSION['rol'] !== "administrador") {
    header("Location: ../login.php");
    exit();
}

// Obtener lista de profesores
$profesores = $conexion->query("
    SELECT u.id_usuario, u.nombre
    FROM tb_usuarios u
    INNER JOIN tb_rol r ON u.id_rol = r.id_rol
    WHERE r.nombre = 'profesor'
");

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_profesor = $_POST['id_profesor'];
    $tema = $_POST['tema'];
    $fecha = $_POST['fecha'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fin = $_POST['hora_fin'];
    $cupos = $_POST['cupos'];
    $estado = $_POST['estado'];

    $stmt = $conexion->prepare("
        INSERT INTO tb_tutorias (id_profesor, tema, fecha, hora_inicio, hora_fin, cupos, estado)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("issssis", $id_profesor, $tema, $fecha, $hora_inicio, $hora_fin, $cupos, $estado);
    
    if ($stmt->execute()) {
        $mensaje = "Tutoría creada correctamente.";
    } else {
        $mensaje = "Error al crear la tutoría.";
    }
    $stmt->close();
}
?>

<?php include "../includes/header.php"; ?>
<?php include "../includes/navbar.php"; ?>

<div class="container py-5">
    <h4 class="mb-4">Crear Nueva Tutoría</h4>

    <?php if ($mensaje): ?>
        <div class="alert alert-info"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="POST" class="bg-white p-4 rounded-3 shadow-sm border border-primary">
        <div class="row g-3">

            <!-- Profesor y Tema -->
            <div class="col-md-6">
                <label class="form-label">Profesor</label>
                <select name="id_profesor" class="form-select" required>
                    <option value="">Seleccionar profesor</option>
                    <?php while ($prof = $profesores->fetch_assoc()): ?>
                        <option value="<?php echo $prof['id_usuario']; ?>">
                            <?php echo htmlspecialchars($prof['nombre']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Tema</label>
                <input type="text" name="tema" class="form-control" required>
            </div>

            <!-- Fecha y Horarios -->
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

            <!-- Cupos y Estado -->
            <div class="col-md-6">
                <label class="form-label">Cupos</label>
                <input type="number" name="cupos" class="form-control" min="1" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-select" required>
                    <option value="disponible">Disponible</option>
                    <option value="reservada">Reservada</option>
                    <option value="cancelada">Cancelada</option>
                </select>
            </div>

            <!-- Botón de envío centrado -->
            <div class="col-12 text-center mt-3">
                <button type="submit" class="btn btn-success w-50">Crear Tutoría</button>
            </div>

        </div>
    </form>
</div>

<?php include "../includes/footer.php"; ?>