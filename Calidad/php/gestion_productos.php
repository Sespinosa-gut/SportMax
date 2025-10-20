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
            $precio = floatval($_POST['precio']);
            $stock = intval($_POST['stock']);
            $id_tipo = intval($_POST['id_tipo']);
            
            // Validar datos
            if (empty($nombre) || $precio <= 0 || $stock < 0 || $id_tipo <= 0) {
                throw new Exception('Todos los campos son requeridos y deben ser válidos');
            }
            
            if ($accion === 'crear') {
                $stmt = $conexion->prepare("
                    INSERT INTO productos (nombre, descripcion, precio, stock, id_tipo) 
                    VALUES (?, ?, ?, ?, ?)
                ");
                $resultado = $stmt->execute([$nombre, $descripcion, $precio, $stock, $id_tipo]);
                $mensaje = 'Producto creado correctamente';
            } else {
                $id = intval($_POST['id']);
                $stmt = $conexion->prepare("
                    UPDATE productos 
                    SET nombre = ?, descripcion = ?, precio = ?, stock = ?, id_tipo = ? 
                    WHERE id = ?
                ");
                $resultado = $stmt->execute([$nombre, $descripcion, $precio, $stock, $id_tipo, $id]);
                $mensaje = 'Producto actualizado correctamente';
            }
            
            if ($resultado) {
                $tipo_mensaje = 'success';
            } else {
                throw new Exception('Error al guardar el producto');
            }
        } elseif ($accion === 'eliminar') {
            $id = intval($_POST['id']);
            $stmt = $conexion->prepare("DELETE FROM productos WHERE id = ?");
            $resultado = $stmt->execute([$id]);
            
            if ($resultado) {
                $mensaje = 'Producto eliminado correctamente';
                $tipo_mensaje = 'success';
            } else {
                throw new Exception('Error al eliminar el producto');
            }
        }
    } catch (Exception $e) {
        $mensaje = $e->getMessage();
        $tipo_mensaje = 'danger';
    }
}

// Obtener productos
$stmt = $conexion->query("
    SELECT p.*, tp.nombre as tipo_nombre 
    FROM productos p 
    JOIN tipos_productos tp ON p.id_tipo = tp.id 
    ORDER BY p.nombre
");
$productos = $stmt->fetchAll();

// Obtener tipos para el formulario
$stmt = $conexion->query("SELECT * FROM tipos_productos ORDER BY nombre");
$tipos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos - Sport Max</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../css/estilos.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primario">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="../index.php">
                <i class="fas fa-dumbbell me-2"></i>Sport Max - Productos
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="../index.php">
                    <i class="fas fa-home me-1"></i>Ir al Sitio
                </a>
                <a class="nav-link" href="dashboard.php">
                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                </a>
                <a class="nav-link" href="gestion_tipos.php">
                    <i class="fas fa-tags me-1"></i>Tipos
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
                            <a class="nav-link activo" href="gestion_productos.php">
                                <i class="fas fa-box me-2"></i>Productos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="gestion_tipos.php">
                                <i class="fas fa-tags me-2"></i>Tipos de Productos
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Gestión de Productos</h1>
                    <button class="btn btn-primario" data-bs-toggle="modal" data-bs-target="#modalProducto">
                        <i class="fas fa-plus me-2"></i>Nuevo Producto
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
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Tipo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($productos as $producto): ?>
                            <tr>
                                <td><?php echo $producto['id']; ?></td>
                                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                                <td>
                                    <span class="badge <?php echo $producto['stock'] < 10 ? 'bg-warning' : 'bg-success'; ?>">
                                        <?php echo $producto['stock']; ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($producto['tipo_nombre']); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary me-1" onclick="editarProducto(<?php echo htmlspecialchars(json_encode($producto)); ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="eliminarProducto(<?php echo $producto['id']; ?>)">
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

    <div class="modal fade" id="modalProducto" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloModalProducto">Nuevo Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="formProducto">
                    <div class="modal-body">
                        <input type="hidden" id="idProducto" name="id">
                        <input type="hidden" id="accionProducto" name="accion" value="crear">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombreProducto" class="form-label">Nombre *</label>
                                <input type="text" class="form-control" id="nombreProducto" name="nombre" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="precioProducto" class="form-label">Precio *</label>
                                <input type="number" class="form-control" id="precioProducto" name="precio" step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="descripcionProducto" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcionProducto" name="descripcion" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="stockProducto" class="form-label">Stock *</label>
                                <input type="number" class="form-control" id="stockProducto" name="stock" min="0" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tipoProducto" class="form-label">Tipo *</label>
                                <select class="form-select" id="tipoProducto" name="id_tipo" required>
                                    <option value="">Seleccionar tipo</option>
                                    <?php foreach ($tipos as $tipo): ?>
                                    <option value="<?php echo $tipo['id']; ?>"><?php echo htmlspecialchars($tipo['nombre']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
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
        function editarProducto(producto) {
            document.getElementById('idProducto').value = producto.id;
            document.getElementById('nombreProducto').value = producto.nombre;
            document.getElementById('descripcionProducto').value = producto.descripcion || '';
            document.getElementById('precioProducto').value = producto.precio;
            document.getElementById('stockProducto').value = producto.stock;
            document.getElementById('tipoProducto').value = producto.id_tipo;
            document.getElementById('accionProducto').value = 'editar';
            document.getElementById('tituloModalProducto').textContent = 'Editar Producto';
            
            const modal = new bootstrap.Modal(document.getElementById('modalProducto'));
            modal.show();
        }

        function eliminarProducto(id) {
            if (confirm('¿Está seguro de que desea eliminar este producto?')) {
                document.getElementById('idEliminar').value = id;
                document.getElementById('formEliminar').submit();
            }
        }

        document.getElementById('modalProducto').addEventListener('hidden.bs.modal', function() {
            document.getElementById('formProducto').reset();
            document.getElementById('accionProducto').value = 'crear';
            document.getElementById('tituloModalProducto').textContent = 'Nuevo Producto';
        });
    </script>
</body>
</html>
