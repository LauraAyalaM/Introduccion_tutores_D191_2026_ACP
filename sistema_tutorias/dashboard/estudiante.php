<?php
require_once "../includes/auth.php";
require_once "../config/conexion.php";


if ($_SESSION['rol'] !== "estudiante") {
    header("Location: ../login.php");
    exit();
}

$id_estudiante = $_SESSION['id_usuario'];
$nombre = $_SESSION['nombre'];


if (isset($_GET['reservar'])) {

    $id_tutoria = intval($_GET['reservar']);

    
    $consulta = $conexion->query("
        SELECT cupos, estado
        FROM tb_tutorias
        WHERE id_tutoria = $id_tutoria
    ");

    if ($consulta->num_rows > 0) {

        $datos = $consulta->fetch_assoc();
        $cupos_totales = $datos['cupos'];
        $estado = $datos['estado'];

        if ($estado === 'disponible') {

            
            $conteo = $conexion->query("
                SELECT COUNT(*) as inscritos
                FROM tb_reservas
                WHERE id_tutoria = $id_tutoria
            ");
            $inscritos = $conteo->fetch_assoc()['inscritos'];

            if ($inscritos < $cupos_totales) {

                
                $check = $conexion->query("
                    SELECT * FROM tb_reservas
                    WHERE id_tutoria = $id_tutoria
                    AND id_estudiante = $id_estudiante
                ");

                if ($check->num_rows == 0) {

                    
                    $conexion->query("
                        INSERT INTO tb_reservas (id_tutoria, id_estudiante)
                        VALUES ($id_tutoria, $id_estudiante)
                    ");

                    
                    $conteo2 = $conexion->query("
                        SELECT COUNT(*) as inscritos
                        FROM tb_reservas
                        WHERE id_tutoria = $id_tutoria
                    ");
                    $inscritos_actualizados = $conteo2->fetch_assoc()['inscritos'];

                    
                    if ($inscritos_actualizados >= $cupos_totales) {
                        $conexion->query("
                            UPDATE tb_tutorias
                            SET estado = 'reservada'
                            WHERE id_tutoria = $id_tutoria
                        ");
                    }
                }
            }
        }
    }

    header("Location: dashboard.php");
    exit();
}


if (isset($_GET['cancelar'])) {

    $id_reserva = intval($_GET['cancelar']);

    
    $consulta = $conexion->query("
        SELECT id_tutoria
        FROM tb_reservas
        WHERE id_reserva = $id_reserva
        AND id_estudiante = $id_estudiante
    ");

    if ($consulta->num_rows > 0) {

        $datos = $consulta->fetch_assoc();
        $id_tutoria = $datos['id_tutoria'];

        
        $conexion->query("
            DELETE FROM tb_reservas
            WHERE id_reserva = $id_reserva
        ");

        
        $conteo = $conexion->query("
            SELECT COUNT(*) as inscritos
            FROM tb_reservas
            WHERE id_tutoria = $id_tutoria
        ");
        $inscritos = $conteo->fetch_assoc()['inscritos'];

        $tutoria = $conexion->query("
            SELECT cupos
            FROM tb_tutorias
            WHERE id_tutoria = $id_tutoria
        ");
        $cupos_totales = $tutoria->fetch_assoc()['cupos'];

        if ($inscritos < $cupos_totales) {
            $conexion->query("
                UPDATE tb_tutorias
                SET estado = 'disponible'
                WHERE id_tutoria = $id_tutoria
            ");
        }
    }

    header("Location: dashboard.php");
    exit();
}


$tutorias = $conexion->query("
    SELECT t.*, u.nombre as profesor,
    (t.cupos - (SELECT COUNT(*) FROM tb_reservas r
                WHERE r.id_tutoria = t.id_tutoria)) as cupos_disponibles
    FROM tb_tutorias t
    INNER JOIN tb_usuarios u ON t.id_profesor = u.id_usuario
    WHERE t.estado IN ('disponible','reservada')
");


$mis_reservas = $conexion->query("
    SELECT r.id_reserva, t.tema, t.fecha, t.hora_inicio, t.hora_fin
    FROM tb_reservas r
    INNER JOIN tb_tutorias t ON r.id_tutoria = t.id_tutoria
    WHERE r.id_estudiante = $id_estudiante
");
?>

<?php include "../includes/header.php"; ?>
<?php include "../includes/navbar.php"; ?>

<div class="container py-5">

    <div class="mb-5">
        <h4 class="mb-0">Panel Estudiante</h4>
        <small class="text-muted">Bienvenido, <?php echo htmlspecialchars($nombre); ?></small>
    </div>

    
    <div class="mb-5">
        <h6 class="text-uppercase text-muted mb-3">Tutorías</h6>

        <div class="row g-4">
            <?php while ($t = $tutorias->fetch_assoc()): ?>

                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm rounded-4 border-start border-4 
                        <?php echo ($t['estado']=='disponible') ? 'border-success' : 'border-warning'; ?>">
                        <div class="card-body d-flex flex-column">

                            <span class="badge 
                                <?php echo ($t['estado']=='disponible') ? 'bg-success' : 'bg-warning text-dark'; ?>">
                                <?php echo ucfirst($t['estado']); ?>
                            </span>

                            <h5 class="fw-semibold mt-2">
                                <?php echo htmlspecialchars($t['tema']); ?>
                            </h5>

                            <p class="text-muted mb-1">
                                Profesor: <?php echo htmlspecialchars($t['profesor']); ?>
                            </p>

                            <p class="mb-1">Fecha: <?php echo $t['fecha']; ?></p>
                            <p class="mb-2">Horario: <?php echo $t['hora_inicio']; ?> - <?php echo $t['hora_fin']; ?></p>

                            <p class="mb-3">
                                Cupos disponibles:
                                <strong><?php echo $t['cupos_disponibles']; ?></strong>
                            </p>

                            <div class="mt-auto">
                                <?php if ($t['estado'] == 'disponible' && $t['cupos_disponibles'] > 0): ?>
                                    <a href="?reservar=<?php echo $t['id_tutoria']; ?>"
                                       class="btn btn-success w-100"
                                       onclick="return confirm('¿Deseas reservar esta tutoría?');">
                                       Reservar
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-secondary w-100" disabled>
                                        Sin cupos disponibles
                                    </button>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                </div>

            <?php endwhile; ?>
        </div>
    </div>

    
    <div>
        <h6 class="text-uppercase text-muted mb-3">Mis Reservas</h6>

        <?php if ($mis_reservas->num_rows > 0): ?>
            <div class="row g-4">
                <?php while ($r = $mis_reservas->fetch_assoc()): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm rounded-4 border-start border-4 border-primary">
                            <div class="card-body d-flex flex-column">

                                <span class="badge bg-primary align-self-start">
                                    Reservada
                                </span>

                                <h5 class="fw-semibold mt-2">
                                    <?php echo htmlspecialchars($r['tema']); ?>
                                </h5>

                                <p class="mb-1">Fecha: <?php echo $r['fecha']; ?></p>
                                <p class="mb-3">Horario: <?php echo $r['hora_inicio']; ?> - <?php echo $r['hora_fin']; ?></p>

                                <div class="mt-auto">
                                    <a href="?cancelar=<?php echo $r['id_reserva']; ?>"
                                       class="btn btn-outline-danger w-100"
                                       onclick="return confirm('¿Cancelar esta reserva?');">
                                       Cancelar Reserva
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="bg-white p-4 rounded-3 shadow-sm text-center">
                <p class="text-muted mb-0">No tienes reservas activas.</p>
            </div>
        <?php endif; ?>
    </div>

</div>

<?php include "../includes/footer.php"; ?>
