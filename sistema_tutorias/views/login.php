<?php
session_start();

if(isset($_SESSION['id_usuario'])){
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login Tutorías</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">

<div class="card shadow p-4" style="width:400px">

<h4 class="text-center mb-4">Sistema de Tutorías</h4>

<form method="POST" action="../controllers/AuthController.php">

<div class="mb-3">
<label>Correo</label>
<input type="email" name="correo" class="form-control" required>
</div>

<div class="mb-3">
<label>Contraseña</label>
<input type="password" name="password" class="form-control" required>
</div>

<button class="btn btn-primary w-100" name="login">
Iniciar Sesión
</button>

</form>

</div>
</div>

</body>
</html>