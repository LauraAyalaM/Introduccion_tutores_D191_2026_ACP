<?php
// reservas/listar.php
require_once "../includes/auth.php";
require_once "../config/conexion.php";

// Validar que sea administrador
verificarRol("administrador");

// FILTRO DE ESTADO (opcional)
$estado_filtro = isset($_GET['estado']) ? $_GET['estado'] : '';

$where_sql = '';
if ($estado_filtro !== '') {
    $where_sql = "WHERE r.estado = '" . $conexion->real_escape_string($estado_filtro) . "'";
}

// Consulta todas las reservas con datos de estudiante, tutoría y profesor
$sql = "SELECT 
            r.id_reserva, 
            r.id_tutoria, 
            r.id_estudiante, 
            r.estado, 
            r.fecha_reserva,
            u.nombre AS estudiante,
            t.tema AS tutoria,
            t.fecha AS fecha_tutoria,
            t.hora_inicio,
            t.hora_fin,
            p.nombre AS nombre_profesor
        FROM tb_reservas r
        INNER JOIN tb_usuarios u ON r.id_estudiante = u.id_usuario
        INNER JOIN tb_tutorias t ON r.id_tutoria = t.id_tutoria
        INNER JOIN tb_usuarios p ON t.id_profesor = p.id_usuario
        $where_sql
        ORDER BY r.fecha_reserva DESC";

$result = $conexion->query($sql);
?>

<?php include "../includes/header.php"; ?>
<?php include "../includes/navbar.php"; ?>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-semibold mb-0">Gestión de Reservas</h4>
            <small class="text-muted">Administra todas las reservas del sistema</small>
        </div>
    </div>

    <!-- FILTRO -->
    <form method="GET" class="mb-4 d-flex gap-2 flex-wrap align-items-center">
        <select name="estado" class="form-select w-auto">
            <option value="">Todos los estados</option>
            <option value="activa" <?php if($estado_filtro=='activa') echo 'selected'; ?>>Activa</option>
            <option value="cancelada" <?php if($estado_filtro=='cancelada') echo 'selected'; ?>>Cancelada</option>
            <option value="asistida" <?php if($estado_filtro=='asistida') echo 'selected'; ?>>Asistida</option>
        </select>

        <button type="submit" class="btn btn-primary">Filtrar</button>
        <a href="listar.php" class="btn btn-secondary">Resetear</a>
    </form>

    <?php if ($result->num_rows > 0) : ?>
        <div class="bg-white rounded-3 shadow-sm p-4">

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="border-bottom">
                        <tr class="text-muted small text-uppercase">
                            <th>ID Reserva</th>
                            <th>Estudiante</th>
                            <th>ID Tutoría</th>
                            <th>Profesor</th>
                            <th>Tutoría</th>
                            <th>Fecha Tutoría</th>
                            <th>Hora Inicio</th>
                            <th>Hora Fin</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($reserva = $result->fetch_assoc()) : ?>
                            <tr>
                                <td class="fw-semibold">#<?= $reserva['id_reserva']; ?></td>
                                <td><?= htmlspecialchars($reserva['estudiante']); ?></td>
                                <td><?= $reserva['id_tutoria']; ?></td>
                                <td><?= htmlspecialchars($reserva['nombre_profesor']); ?></td>
                                <td><?= htmlspecialchars($reserva['tutoria']); ?></td>
                                <td><?= $reserva['fecha_tutoria']; ?></td>
                                <td><?= $reserva['hora_inicio']; ?></td>
                                <td><?= $reserva['hora_fin']; ?></td>
                                <td>
                                    <?php 
                                    switch($reserva['estado']){
                                        case 'activa': echo '<span class="badge bg-success">Activa</span>'; break;
                                        case 'cancelada': echo '<span class="badge bg-danger">Cancelada</span>'; break;
                                        case 'asistida': echo '<span class="badge bg-primary">Asistida</span>'; break;
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        </div>
    <?php else : ?>
        <div class="bg-white rounded-3 shadow-sm p-4 text-center">
            <p class="mb-2 text-muted">No hay reservas registradas.</p>
        </div>
    <?php endif; ?>

</div>

<?php include "../includes/footer.php"; ?>