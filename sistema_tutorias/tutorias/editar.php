<?php
require_once "../includes/auth.php";
require_once "../config/conexion.php";

// Solo administrador
if ($_SESSION['rol'] !== "administrador") {
    header("Location: ../login.php");
    exit();
}

$id_tutoria = $_GET['id'] ?? null;
if (!$id_tutoria) {
    header("Location: listar.php");
    exit();
}

// Obtener tutoría
$stmt = $conexion->prepare("SELECT * FROM tb_tutorias WHERE id_tutoria = ?");
$stmt->bind_param("i", $id_tutoria);
$stmt->execute();
$result = $stmt->get_result();
$tutoria = $result->fetch_assoc();
$stmt->close();

if (!$tutoria) {
    header("Location: listar.php");
    exit();
}

// Lista de profesores
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
        UPDATE tb_tutorias
        SET id_profesor = ?, tema = ?, fecha = ?, hora_inicio = ?, hora_fin = ?, cupos = ?, estado = ?
        WHERE id_tutoria = ?
    ");
    $stmt->bind_param("issssisi", $id_profesor, $tema, $fecha, $hora_inicio, $hora_fin, $cupos, $estado, $id_tutoria);
    
    if ($stmt->execute()) {
        $mensaje = "Tutoría actualizada correctamente.";
        $tutoria = array_merge($tutoria, $_POST); // actualizar valores mostrados
    } else {
        $mensaje = "Error al actualizar la tutoría.";
    }
    $stmt->close();
}
?>

<?php include "../includes/header.php"; ?>
<?php include "../includes/navbar.php"; ?>

<div class="container py-5">
    <h4 class="mb-4">Editar Tutoría</h4>

    <?php if ($mensaje): ?>
        <div class="alert alert-info"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="POST" class="bg-white p-4 rounded-3 shadow-sm border border-warning">
        <div class="row g-3">

            <!-- Profesor y Tema -->
            <div class="col-md-6">
                <label class="form-label">Profesor</label>
                <select name="id_profesor" class="form-select" required>
                    <?php while ($prof = $profesores->fetch_assoc()): ?>
                        <option value="<?php echo $prof['id_usuario']; ?>" 
                            <?php if($prof['id_usuario'] == $tutoria['id_profesor']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($prof['nombre']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Tema</label>
                <input type="text" name="tema" class="form-control" required 
                    value="<?php echo htmlspecialchars($tutoria['tema']); ?>">
            </div>

            <!-- Fecha y Horarios -->
            <div class="col-md-4">
                <label class="form-label">Fecha</label>
                <input type="date" name="fecha" class="form-control" required 
                    value="<?php echo $tutoria['fecha']; ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label">Hora Inicio</label>
                <input type="time" name="hora_inicio" class="form-control" required 
                    value="<?php echo $tutoria['hora_inicio']; ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label">Hora Fin</label>
                <input type="time" name="hora_fin" class="form-control" required 
                    value="<?php echo $tutoria['hora_fin']; ?>">
            </div>

            <!-- Cupos y Estado -->
            <div class="col-md-6">
                <label class="form-label">Cupos</label>
                <input type="number" name="cupos" class="form-control" min="1" required 
                    value="<?php echo $tutoria['cupos']; ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-select" required>
                    <option value="disponible" <?php if($tutoria['estado']=='disponible') echo 'selected'; ?>>Disponible</option>
                    <option value="reservada" <?php if($tutoria['estado']=='reservada') echo 'selected'; ?>>Reservada</option>
                    <option value="cancelada" <?php if($tutoria['estado']=='cancelada') echo 'selected'; ?>>Cancelada</option>
                </select>
            </div>

            <!-- Botón -->
            <div class="col-12 text-center mt-3">
                <button type="submit" class="btn btn-warning w-50">Actualizar Tutoría</button>
            </div>

        </div>
    </form>
</div>

<?php include "../includes/footer.php"; ?>