<?php
require_once "config/conexion.php";
require_once "includes/auth.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $password = trim($_POST['password']);
    $rol = $_POST['rol'];

    if (empty($nombre) || empty($correo) || empty($password) || empty($rol)) {
        $error = "Todos los campos son obligatorios.";
    } else {

        // Verificar si el correo ya existe
        $stmt = $conexion->prepare("SELECT id_usuario FROM tb_usuarios WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $error = "El correo ya está registrado.";
        } else {

            // Obtener id del rol
            $stmtRol = $conexion->prepare("SELECT id_rol FROM tb_rol WHERE nombre_rol = ?");
            $stmtRol->bind_param("s", $rol);
            $stmtRol->execute();
            $resultadoRol = $stmtRol->get_result();

            if ($resultadoRol->num_rows === 1) {

                $filaRol = $resultadoRol->fetch_assoc();
                $id_rol = $filaRol['id_rol'];

                // Insertar usuario (SIN HASH)
                $estado = "activo";

                $stmtInsert = $conexion->prepare("INSERT INTO tb_usuarios (nombre, correo, password, id_rol, estado) VALUES (?, ?, ?, ?, ?)");
                $stmtInsert->bind_param("sssis", $nombre, $correo, $password, $id_rol, $estado);

                if ($stmtInsert->execute()) {
                    $success = "Registro exitoso. Ahora puedes iniciar sesión.";
                } else {
                    $error = "Error al registrar el usuario.";
                }

                $stmtInsert->close();
            } else {
                $error = "Rol inválido.";
            }

            $stmtRol->close();
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro | Sistema de Tutorías</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-sm p-4" style="width: 450px;">
        
        <h3 class="text-center mb-4">Registro de Usuario</h3>

        <?php if (!empty($error)) : ?>
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)) : ?>
            <div class="alert alert-success">
                <?php echo $success; ?>
                <br>
                <a href="login.php">Ir al login</a>
            </div>
        <?php endif; ?>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Nombre completo</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Correo electrónico</label>
                <input type="email" name="correo" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Rol</label>
                <select name="rol" class="form-select" required>
                    <option value="">Seleccione un rol</option>
                    <option value="estudiante">Estudiante</option>
                    <option value="profesor">Profesor</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                Registrarse
            </button>

        </form>

        <div class="text-center mt-3">
            <small>¿Ya tienes cuenta? 
                <a href="login.php">Inicia sesión aquí</a>
            </small>
        </div>

    </div>
</div>

</body>
</html>