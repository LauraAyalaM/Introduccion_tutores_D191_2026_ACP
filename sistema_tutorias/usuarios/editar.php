<?php
require_once "../includes/auth.php";
require_once "../config/conexion.php";

// Validar administrador
if ($_SESSION['rol'] !== "administrador") {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: listar.php");
    exit();
}

$id_usuario = intval($_GET['id']);
$mensaje = "";

// Obtener datos del usuario
$stmt = $conexion->prepare("SELECT * FROM tb_usuarios WHERE id_usuario = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    header("Location: listar.php");
    exit();
}

$usuario = $resultado->fetch_assoc();
$stmt->close();

// Obtener roles
$roles = $conexion->query("SELECT * FROM tb_rol");

// Procesar actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $password = trim($_POST['password']);
    $id_rol = $_POST['id_rol'];

    if (empty($nombre) || empty($correo) || empty($id_rol)) {
        $mensaje = "<div class='alert alert-danger'>Nombre, correo y rol son obligatorios.</div>";
    } else {

        // Si se escribe nueva contraseña → actualizar
        if (!empty($password)) {
            $stmt = $conexion->prepare("UPDATE tb_usuarios 
                                        SET nombre=?, correo=?, password=?, id_rol=? 
                                        WHERE id_usuario=?");
            $stmt->bind_param("sssii", $nombre, $correo, $password, $id_rol, $id_usuario);
        } else {
            // Si no se escribe contraseña → no modificarla
            $stmt = $conexion->prepare("UPDATE tb_usuarios 
                                        SET nombre=?, correo=?, id_rol=? 
                                        WHERE id_usuario=?");
            $stmt->bind_param("ssii", $nombre, $correo, $id_rol, $id_usuario);
        }

        if ($stmt->execute()) {
            $mensaje = "<div class='alert alert-success'>Usuario actualizado correctamente.</div>";

            // Recargar datos actualizados
            $stmt2 = $conexion->prepare("SELECT * FROM tb_usuarios WHERE id_usuario = ?");
            $stmt2->bind_param("i", $id_usuario);
            $stmt2->execute();
            $usuario = $stmt2->get_result()->fetch_assoc();
            $stmt2->close();

        } else {
            $mensaje = "<div class='alert alert-danger'>Error al actualizar el usuario.</div>";
        }

        $stmt->close();
    }
}
?>

<?php include "../includes/header.php"; ?>
<?php include "../includes/navbar.php"; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <div class="card shadow-sm border-1">
                <div class="card-header bg-white text-center">
                    <h5 class="mb-0 fw-semibold">Editar Usuario</h5>
                </div>

                <div class="card-body px-4 py-4">

                    <?php echo $mensaje; ?>

                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control"
                                   value="<?php echo $usuario['nombre']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Correo electrónico</label>
                            <input type="email" name="correo" class="form-control"
                                   value="<?php echo $usuario['correo']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Nueva Contraseña
                                <small class="text-muted">(Opcional)</small>
                            </label>
                            <input type="text" name="password" class="form-control">
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Rol</label>
                            <select name="id_rol" class="form-select" required>
                                <?php while ($rol = $roles->fetch_assoc()) : ?>
                                    <option value="<?php echo $rol['id_rol']; ?>"
                                        <?php if ($usuario['id_rol'] == $rol['id_rol']) echo "selected"; ?>>
                                        <?php echo ucfirst($rol['nombre']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="listar.php" class="btn btn-outline-secondary">
                                Volver
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Actualizar Usuario
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>