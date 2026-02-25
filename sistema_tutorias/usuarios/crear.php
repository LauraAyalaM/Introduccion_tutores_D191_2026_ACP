<?php
require_once "../includes/auth.php";
require_once "../config/conexion.php";

// Validar administrador
if ($_SESSION['rol'] !== "administrador") {
    header("Location: ../login.php");
    exit();
}

$mensaje = "";

// Obtener roles
$roles = $conexion->query("SELECT * FROM tb_rol");

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['correo']);
    $password = trim($_POST['password']);
    $id_rol = $_POST['id_rol'];

    if (empty($nombre) || empty($email) || empty($password) || empty($id_rol)) {
        $mensaje = "<div class='alert alert-danger'>Todos los campos son obligatorios.</div>";
    } else {

        // Verificar si el email ya existe
        $verificar = $conexion->prepare("SELECT id_usuario FROM tb_usuarios WHERE correo = ?");
        $verificar->bind_param("s", $email);
        $verificar->execute();
        $verificar->store_result();

        if ($verificar->num_rows > 0) {
            $mensaje = "<div class='alert alert-warning'>El correo ya está registrado.</div>";
        } else {

            // Insertar usuario (SIN HASH como solicitaste)
            $stmt = $conexion->prepare("INSERT INTO tb_usuarios (nombre, correo, password, id_rol) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $nombre, $email, $password, $id_rol);

            if ($stmt->execute()) {
                $mensaje = "<div class='alert alert-success'>Usuario creado correctamente.</div>";
            } else {
                $mensaje = "<div class='alert alert-danger'>Error al crear el usuario.</div>";
            }

            $stmt->close();
        }

        $verificar->close();
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
                    <h5 class="mb-0 fw-semibold">Crear Nuevo Usuario</h5>
                </div>

                <div class="card-body px-4 py-4">

                    <?php echo $mensaje; ?>

                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Correo electrónico</label>
                            <input type="correo" name="correo" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="text" name="password" class="form-control" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Rol</label>
                            <select name="id_rol" class="form-select" required>
                                <option value="">Seleccione un rol</option>
                                <?php while ($rol = $roles->fetch_assoc()) : ?>
                                    <option value="<?php echo $rol['id_rol']; ?>">
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
                                Crear Usuario
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>