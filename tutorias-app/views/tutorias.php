<?php
session_start();
include "../config/db.php";
include "../includes/header.php";

// Obtener tutorías
$sql = "SELECT t.*, u.nombre as profesor
        FROM tb_tutorias t
        INNER JOIN tb_usuarios u ON t.id_profesor = u.id_usuario
        ORDER BY t.fecha DESC";

$result = $conn->query($sql);
?>

<h3 class="mb-4">📚 Tutorías disponibles</h3>

<div class="table-responsive">
<table class="table table-bordered table-hover bg-white">

<thead class="table-dark">
<tr>
    <th>Fecha</th>
    <th>Hora</th>
    <th>Profesor</th>
    <th>Tema</th>
    <th>Cupos</th>
    <th>Estado</th>
    <th>Acción</th>
</tr>
</thead>

<tbody>

<?php while($row = $result->fetch_assoc()): ?>

<tr>
    <td><?= $row['fecha'] ?></td>

    <td>
        <?= $row['hora_inicio'] ?> -
        <?= $row['hora_fin'] ?>
    </td>

    <td><?= $row['profesor'] ?></td>

    <td><?= $row['tema'] ?></td>

    <td><?= $row['cupos'] ?></td>

    <td>

        <?php
        $estado = $row['estado'];

        if($estado == "disponible"){
            echo '<span class="badge bg-success">Disponible</span>';
        }
        elseif($estado == "reservada"){
            echo '<span class="badge bg-warning text-dark">Reservada</span>';
        }
        else{
            echo '<span class="badge bg-danger">Cancelada</span>';
        }
        ?>

    </td>

    <td>

    <?php if($estado == "disponible" && $row['cupos'] > 0): ?>

        <a class="btn btn-sm btn-primary"
           href="../controllers/reservas.php?id_tutoria=<?= $row['id_tutoria'] ?>">
           Reservar
        </a>

    <?php else: ?>

        <button class="btn btn-sm btn-secondary" disabled>
            No disponible
        </button>

    <?php endif; ?>

    </td>

</tr>

<?php endwhile; ?>

</tbody>
</table>
</div>

<?php include "../includes/footer.php"; ?>