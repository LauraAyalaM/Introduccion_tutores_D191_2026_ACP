<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Reserva</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-sm p-4">
        <h3 class="mb-4">Editar Reserva</h3>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">ID Tutoría</label>
                <input type="number" 
                       name="id_tutoria" 
                       class="form-control"
                       value="<?php echo $reserva['id_tutoria']; ?>" 
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Fecha</label>
                <input type="date" 
                       name="fecha_reserva" 
                       class="form-control"
                       value="<?php echo $reserva['fecha_reserva']; ?>" 
                       required>
            </div>

            <button type="submit" class="btn btn-primary">
                Actualizar
            </button>

            <a href="mis_reservas.php" class="btn btn-secondary">
                Volver
            </a>
        </form>

    </div>
</div>

</body>
</html>
