<?php
require_once 'config.php';
verificarAdmin();

$db = ConexionDB::obtenerInstancia();
$conexion = $db->obtenerConexion();

$mensaje = '';
$tipo_mensaje = '';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    
    try {
        if ($accion === 'crear' || $accion === 'editar') {
            $nombre = trim($_POST['nombre']);
            $descripcion = trim($_POST['descripcion']);
            
            // Validar datos
            if (empty($nombre)) {
                throw new Exception('El nombre es requerido');
            }
            
            if ($accion === 'crear') {
                $stmt = $conexion->prepare("
                    INSERT INTO tipos_productos (nombre, descripcion) 
                    VALUES (?, ?)
                ");
                $resultado = $stmt->execute([$nombre, $descripcion]);
                $mensaje = 'Tipo creado correctamente';
            } else {
                $id = intval($_POST['id']);
                $stmt = $conexion->prepare("
                    UPDATE tipos_productos 
                    SET nombre = ?, descripcion = ? 
                    WHERE id = ?
                ");
                $resultado = $stmt->execute([$nombre, $descripcion, $id]);
                $mensaje = 'Tipo actualizado correctamente';
            }
            
            if ($resultado) {
                $tipo_mensaje = 'success';
            } else {
                throw new Exception('Error al guardar el tipo');
            }
        } elseif ($accion === 'eliminar') {
            $id = intval($_POST['id']);
            
            // Verificar si hay productos asociados
            $stmt = $conexion->prepare("SELECT COUNT(*) as count FROM productos WHERE id_tipo = ?");
            $stmt->execute([$id]);
            $resultado = $stmt->fetch();
            
            if ($resultado['count'] > 0) {
                throw new Exception('No se puede eliminar el tipo porque tiene productos asociados');
            }
            
            $stmt = $conexion->prepare("DELETE FROM tipos_productos WHERE id = ?");
            $resultado = $stmt->execute([$id]);
            
            if ($resultado) {
                $mensaje = 'Tipo eliminado correctamente';
                $tipo_mensaje = 'success';
            } else {
                throw new Exception('Error al eliminar el tipo');
            }
        }
    } catch (Exception $e) {
        $mensaje = $e->getMessage();
        $tipo_mensaje = 'danger';
    }
}

// Obtener tipos
$stmt = $conexion->query("SELECT * FROM tipos_productos ORDER BY nombre");
$tipos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Tipos - Sport Max</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../css/estilos.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primario">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="../index.php">
                <i class="fas fa-dumbbell me-2"></i>Sport Max - Tipos
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="../index.php">
                    <i class="fas fa-home me-1"></i>Ir al Sitio
                </a>
                <a class="nav-link" href="dashboard.php">
                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                </a>
                <a class="nav-link" href="gestion_productos.php">
                    <i class="fas fa-box me-1"></i>Productos
                </a>
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-sign-out-alt me-1"></i>Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block bg-claro sidebar">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="gestion_productos.php">
                                <i class="fas fa-box me-2"></i>Productos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link activo" href="gestion_tipos.php">
                                <i class="fas fa-tags me-2"></i>Tipos de Productos
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Gestión de Tipos de Productos</h1>
                    <button class="btn btn-primario" data-bs-toggle="modal" data-bs-target="#modalTipo">
                        <i class="fas fa-plus me-2"></i>Nuevo Tipo
                    </button>
                </div>

                <?php if ($mensaje): ?>
                    <div class="alert alert-<?php echo $tipo_mensaje; ?> alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($mensaje); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tipos as $tipo): ?>
                            <tr>
                                <td><?php echo $tipo['id']; ?></td>
                                <td><?php echo htmlspecialchars($tipo['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($tipo['descripcion'] ?: '-'); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary me-1" onclick="editarTipo(<?php echo htmlspecialchars(json_encode($tipo)); ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="eliminarTipo(<?php echo $tipo['id']; ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <div class="modal fade" id="modalTipo" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloModalTipo">Nuevo Tipo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="formTipo">
                    <div class="modal-body">
                        <input type="hidden" id="idTipo" name="id">
                        <input type="hidden" id="accionTipo" name="accion" value="crear">
                        <div class="mb-3">
                            <label for="nombreTipo" class="form-label">Nombre *</label>
                            <input type="text" class="form-control" id="nombreTipo" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcionTipo" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcionTipo" name="descripcion" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primario">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <form method="POST" id="formEliminar" style="display: none;">
        <input type="hidden" name="accion" value="eliminar">
        <input type="hidden" name="id" id="idEliminar">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editarTipo(tipo) {
            document.getElementById('idTipo').value = tipo.id;
            document.getElementById('nombreTipo').value = tipo.nombre;
            document.getElementById('descripcionTipo').value = tipo.descripcion || '';
            document.getElementById('accionTipo').value = 'editar';
            document.getElementById('tituloModalTipo').textContent = 'Editar Tipo';
            
            const modal = new bootstrap.Modal(document.getElementById('modalTipo'));
            modal.show();
        }

        function eliminarTipo(id) {
            if (confirm('¿Está seguro de que desea eliminar este tipo?')) {
                document.getElementById('idEliminar').value = id;
                document.getElementById('formEliminar').submit();
            }
        }

        document.getElementById('modalTipo').addEventListener('hidden.bs.modal', function() {
            document.getElementById('formTipo').reset();
            document.getElementById('accionTipo').value = 'crear';
            document.getElementById('tituloModalTipo').textContent = 'Nuevo Tipo';
        });
    </script>
</body>
</html>
