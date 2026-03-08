<?php
require_once "config/conexion.php";
require_once "includes/auth.php";

// Si ya hay sesión activa Y estás en login.php, redirige
if (isset($_SESSION['rol']) && basename($_SERVER['PHP_SELF']) == 'login.php') {
    switch ($_SESSION['rol']) {
        case 'estudiante':
            header("Location: views/reservas/mis_reservas.php");
            exit();
        case 'profesor':
            header("Location: views/tutorias/listar.php");
            exit();
        case 'administrador':
            header("Location: dashboard/administrador.php");
            exit();
    }
}
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $correo = trim($_POST['correo']);
    $password = trim($_POST['password']);

    if (empty($correo) || empty($password)) {
        $error = "Todos los campos son obligatorios.";
    } else {

        $stmt = $conexion->prepare("SELECT u.id_usuario, u.nombre, u.password, r.nombre
                                    FROM tb_usuarios u
                                    INNER JOIN tb_rol r ON u.id_rol = r.id_rol
                                    WHERE u.correo = ? AND u.activo = 1");

        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {

            $usuario = $resultado->fetch_assoc();

            // 🔹 Comparación directa SIN HASH
            if ($password === $usuario['password']) {

                $_SESSION['id_usuario'] = $usuario['id_usuario'];
                $_SESSION['nombre'] = $usuario['nombre'];
                $_SESSION['rol'] = $usuario['nombre'];

                switch ($usuario['nombre']) {
                    case 'estudiante':
                        header("Location: views/reservas/mis_reservas.php");
                        break;
                    case 'profesor':
                        header("Location: views/tutorias/listar.php");
                        break;
                    case 'administrador':
                        header("Location: dashboard/administrador.php");
                        break;
                }
                exit();

            } else {
                $error = "Contraseña incorrecta.";
            }

        } else {
            $error = "Usuario no encontrado o inactivo.";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión | Sistema de Tutorías</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-sm p-4" style="width: 400px;">
        
        <h3 class="text-center mb-4">Iniciar Sesión</h3>

        <?php if (!empty($error)) : ?>
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Correo electrónico</label>
                <input type="email" name="correo" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                Ingresar
            </button>
        </form>

        <div class="text-center mt-3">
            <small>¿No tienes cuenta? 
                <a href="register.php">Regístrate aquí</a>
            </small>
        </div>

    </div>
</div>

</body>
</html>