<?php
session_start();
require_once("../config/conexion.php");

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit();
}

$id_estudiante = $_SESSION['id_usuario'];
$mensaje = "";
$tipo = "";

if (isset($_GET['id_tutoria'])) {

    $id_tutoria = $_GET['id_tutoria'];

   
    $verificar = $conexion->prepare("SELECT * FROM tb_tutorias WHERE id_tutoria = ? AND estado = 'disponible'");
    $verificar->bind_param("i", $id_tutoria);
    $verificar->execute();
    $resultado = $verificar->get_result();

    if ($resultado->num_rows > 0) {

        $stmt = $conexion->prepare("INSERT INTO tb_reservas (id_tutoria, id_estudiante) VALUES (?, ?)");
        $stmt->bind_param("ii", $id_tutoria, $id_estudiante);

        if ($stmt->execute()) {

            $mensaje = " Reserva realizada correctamente.";
            $tipo = "success";

        } else {
            $mensaje = "No se pudo realizar la reserva.";
            $tipo = "danger";
        }

    } else {
        $mensaje = "La tutoría no está disponible.";
        $tipo = "warning";
    }

} else {
    $mensaje = "Tutoría no válida.";
    $tipo = "danger";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reservar Tutoría</title>

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-lg p-4 text-center">

        <h2 class="mb-4">Sistema de Tutorías</h2>

        <?php if ($mensaje != "") { ?>
            <div class="alert alert-<?php echo $tipo; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php } ?>

        <a href="mis_reservas.php" class="btn btn-primary mt-3">
            Ver mis reservas
        </a>

        <a href="../tutorias/listar.php" class="btn btn-secondary mt-3">
            Volver a tutorías
        </a>

    </div>
</div>

</body>
</html>
