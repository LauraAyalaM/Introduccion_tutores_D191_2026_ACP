<?php
require_once "../includes/auth.php";
require_once "../config/conexion.php";

// Validar que sea administrador
if ($_SESSION['rol'] !== "administrador") {
    header("Location: ../login.php");
    exit();
}

// FILTROS
$estado_filtro = isset($_GET['estado']) ? $_GET['estado'] : '';
$rol_filtro = isset($_GET['rol']) ? $_GET['rol'] : '';

// Consulta de usuarios con filtros
$where_clauses = [];
if ($estado_filtro !== '') {
    $where_clauses[] = "u.activo = '" . $conexion->real_escape_string($estado_filtro) . "'";
}
if ($rol_filtro !== '') {
    $where_clauses[] = "r.nombre = '" . $conexion->real_escape_string($rol_filtro) . "'";
}

$where_sql = '';
if (count($where_clauses) > 0) {
    $where_sql = 'WHERE ' . implode(' AND ', $where_clauses);
}

$sql = "SELECT 
            u.id_usuario, 
            u.nombre as nombre_usuario, 
            u.correo, 
            r.nombre as rol_nombre,
            u.activo
        FROM tb_usuarios u
        INNER JOIN tb_rol r ON u.id_rol = r.id_rol
        $where_sql
        ORDER BY u.id_usuario DESC";

$resultado = $conexion->query($sql);
?>

<?php include "../includes/header.php"; ?>
<?php include "../includes/navbar.php"; ?>

<div class="container py-5">

    <!-- Encabezado de sección -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-semibold mb-0">Gestión de Usuarios</h4>
            <small class="text-muted">Administra los usuarios del sistema</small>
        </div>

        <a href="crear.php" class="btn btn-primary">
            + Nuevo Usuario
        </a>
    </div>

    <!-- FILTROS -->
    <form method="GET" class="mb-4 d-flex gap-2 flex-wrap align-items-center">
        <select name="estado" class="form-select w-auto">
            <option value="">Todos los estados</option>
            <option value="1" <?php if($estado_filtro=='1') echo 'selected'; ?>>Activo</option>
            <option value="0" <?php if($estado_filtro=='0') echo 'selected'; ?>>Desactivado</option>
        </select>

        <select name="rol" class="form-select w-auto">
            <option value="">Todos los roles</option>
            <option value="administrador" <?php if($rol_filtro=='administrador') echo 'selected'; ?>>Administrador</option>
            <option value="profesor" <?php if($rol_filtro=='profesor') echo 'selected'; ?>>Profesor</option>
            <option value="estudiante" <?php if($rol_filtro=='estudiante') echo 'selected'; ?>>Estudiante</option>
        </select>

        <button type="submit" class="btn btn-primary">Filtrar</button>
        <a href="listar.php" class="btn btn-secondary">Resetear</a>
    </form>

    <?php if ($resultado->num_rows > 0) : ?>

        <div class="bg-white rounded-3 shadow-sm p-4">

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="border-bottom">
                        <tr class="text-muted small text-uppercase">
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php while ($usuario = $resultado->fetch_assoc()) : ?>
                            <tr>
                                <td class="fw-semibold">#<?php echo $usuario['id_usuario']; ?></td>
                                <td><?php echo htmlspecialchars($usuario['nombre_usuario']); ?></td>
                                <td class="text-muted"><?php echo htmlspecialchars($usuario['correo']); ?></td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <?php echo ucfirst($usuario['rol_nombre']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($usuario['activo']): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Desactivado</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-left gap-2">
                                        <a href="editar.php?id=<?php echo $usuario['id_usuario']; ?>" 
                                           class="btn btn-sm btn-outline-primary">Editar</a>

                                        <?php if ($usuario['activo']): ?>
                                            <a href="cambiar_estado.php?id=<?php echo $usuario['id_usuario']; ?>&estado=0" 
                                               class="btn btn-sm btn-outline-danger"
                                               onclick="return confirm('¿Deseas desactivar este usuario?')">Desactivar</a>
                                        <?php else: ?>
                                            <a href="cambiar_estado.php?id=<?php echo $usuario['id_usuario']; ?>&estado=1" 
                                               class="btn btn-sm btn-outline-success"
                                               onclick="return confirm('¿Deseas reactivar este usuario?')">Activar</a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>

                    </tbody>
                </table>
            </div>

        </div>

    <?php else : ?>

        <div class="bg-white rounded-3 shadow-sm p-4 text-center">
            <p class="mb-2 text-muted">No hay usuarios registrados.</p>
            <a href="crear.php" class="btn btn-primary btn-sm">Crear el primero</a>
        </div>

    <?php endif; ?>

</div>

<?php include "../includes/footer.php"; ?>