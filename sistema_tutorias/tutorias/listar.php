<?php
require_once "../includes/auth.php";
require_once "../config/conexion.php";

$id_usuario = $_SESSION['id_usuario'];
$rol = $_SESSION['rol'];

// FILTROS
$estado_filtro = isset($_GET['estado']) ? $_GET['estado'] : '';
$tema_filtro = isset($_GET['tema']) ? $_GET['tema'] : '';

$where_clauses = [];
if ($estado_filtro != '') {
    $where_clauses[] = "t.estado = '" . $conexion->real_escape_string($estado_filtro) . "'";
}
if ($tema_filtro != '') {
    $where_clauses[] = "t.tema LIKE '%" . $conexion->real_escape_string($tema_filtro) . "%'";
}
$where_sql = '';
if (count($where_clauses) > 0) {
    $where_sql = 'WHERE ' . implode(' AND ', $where_clauses);
}

// CONSULTA TUTORÍAS PARA ADMINISTRADOR
$sql = "
    SELECT t.id_tutoria, t.tema, t.fecha, t.hora_inicio, t.hora_fin, t.cupos, t.estado,
           u.nombre AS profesor
    FROM tb_tutorias t
    INNER JOIN tb_usuarios u ON t.id_profesor = u.id_usuario
    $where_sql
    ORDER BY t.fecha ASC
";

$resultado = $conexion->query($sql);
?>

<?php include "../includes/header.php"; ?>
<?php include "../includes/navbar.php"; ?>

<div class="container py-5">

    <!-- Encabezado -->
    <div class="mb-4">
        <h4 class="fw-semibold mb-0">Tutorías</h4>
        <small class="text-muted">Administración de tutorías académicas</small>
    </div>

    <!-- FILTROS -->
    <form method="GET" class="mb-4 d-flex gap-2 flex-wrap align-items-center">
        <select name="estado" class="form-select w-auto">
            <option value="">Todos los estados</option>
            <option value="disponible" <?php if($estado_filtro=='disponible') echo 'selected'; ?>>Disponible</option>
            <option value="reservada" <?php if($estado_filtro=='reservada') echo 'selected'; ?>>Reservada</option>
            <option value="cancelada" <?php if($estado_filtro=='cancelada') echo 'selected'; ?>>Cancelada</option>
        </select>

        <input type="text" name="tema" placeholder="Buscar por tema" class="form-control w-auto" value="<?php echo htmlspecialchars($tema_filtro); ?>">

        <button type="submit" class="btn btn-primary">Filtrar</button>
        <a href="listar.php" class="btn btn-secondary">Resetear</a>
        <a href="crear.php" class="btn btn-success ms-auto">+ Nueva Tutoría</a>
    </form>

    <?php if ($resultado->num_rows > 0): ?>
        <div class="row g-4">
            <?php while ($tutoria = $resultado->fetch_assoc()): ?>

                <?php
                // Contar reservas actuales
                $stmt = $conexion->prepare("SELECT COUNT(*) as total FROM tb_reservas WHERE id_tutoria = ?");
                $stmt->bind_param("i", $tutoria['id_tutoria']);
                $stmt->execute();
                $reservas = $stmt->get_result()->fetch_assoc()['total'];
                $stmt->close();

                $cupos_disponibles = $tutoria['cupos'] - $reservas;

                // Color del borde según estado
                $border_class = 'border-primary';
                if ($tutoria['estado'] == 'reservada') $border_class = 'border-warning';
                elseif ($tutoria['estado'] == 'cancelada') $border_class = 'border-danger';
                ?>

                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm rounded-3 border-3 <?php echo $border_class; ?>">
                        <div class="card-body d-flex flex-column">
                            <span class="badge bg-<?php 
                                echo ($tutoria['estado']=='disponible')?'success':
                                    (($tutoria['estado']=='reservada')?'warning':'danger'); 
                            ?>">
                                <?php echo ucfirst($tutoria['estado']); ?>
                            </span>
                            <br>
                            <h5 class="fw-semibold"><?php echo htmlspecialchars($tutoria['tema']); ?></h5>
                            <p class="text-muted mb-2">Profesor: <?php echo htmlspecialchars($tutoria['profesor']); ?></p>
                            <p class="mb-1"><strong>Fecha:</strong> <?php echo $tutoria['fecha']; ?></p>
                            <p class="mb-3"><strong>Horario:</strong> <?php echo $tutoria['hora_inicio']; ?> - <?php echo $tutoria['hora_fin']; ?></p>
                            <p>
                                <strong>Cupos disponibles:</strong>
                                <span class="badge bg-<?php echo ($cupos_disponibles > 0) ? 'success' : 'danger'; ?>">
                                    <?php echo $cupos_disponibles; ?>
                                </span>
                            </p>

                            <?php if ($rol == "administrador"): ?>
                                <div class="mt-auto d-flex gap-2">
                                    <a href="editar.php?id=<?php echo $tutoria['id_tutoria']; ?>" class="btn btn-outline-warning w-50">
                                        Editar
                                    </a>
                                    <?php if ($tutoria['estado'] != 'cancelada'): ?>
                                        <a href="cambiar_estado.php?id=<?php echo $tutoria['id_tutoria']; ?>&estado=cancelada" 
                                        class="btn btn-outline-danger w-50"
                                        onclick="return confirm('¿Seguro que deseas cancelar esta tutoría?')">
                                            Cancelar
                                        </a>
                                    <?php else: ?>
                                        <a href="cambiar_estado.php?id=<?php echo $tutoria['id_tutoria']; ?>&estado=disponible" 
                                        class="btn btn-outline-success w-50"
                                        onclick="return confirm('¿Deseas reabrir esta tutoría?')">
                                            Reabrir
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="bg-white p-4 rounded-3 shadow-sm text-center">
            <p class="text-muted mb-0">No hay tutorías registradas.</p>
        </div>
    <?php endif; ?>

</div>

<?php include "../includes/footer.php"; ?>