<?php
require_once 'php/config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sport Max - Tienda Deportiva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="css/estilos.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primario">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="fas fa-dumbbell me-2"></i>Sport Max
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#inicio">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#productos">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#categorias">Categorías</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contacto">Contacto</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['usuario_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($_SESSION['usuario']); ?>
                            </a>
                            <ul class="dropdown-menu">
                                <?php if ($_SESSION['rol'] === 'admin'): ?>
                                    <li><a class="dropdown-item" href="php/dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="php/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="php/login.php">
                                <i class="fas fa-sign-in-alt me-1"></i>Iniciar Sesión
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <section id="inicio" class="hero-section">
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold text-white mb-4">
                        Bienvenido a <span class="texto-destacado">Sport Max</span>
                    </h1>
                    <p class="lead text-white mb-4">
                        Tu tienda deportiva de confianza con los mejores productos para todas las disciplinas deportivas.
                        Encuentra todo lo que necesitas para alcanzar tus metas deportivas.
                    </p>
                    <a href="#productos" class="btn btn-primario btn-lg">
                        <i class="fas fa-shopping-cart me-2"></i>Ver Productos
                    </a>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="fas fa-running hero-icono"></i>
                </div>
            </div>
        </div>
    </section>

    <section id="productos" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5 titulo-seccion">Productos Destacados</h2>
            <div class="row">
                <?php
                $db = ConexionDB::obtenerInstancia();
                $conexion = $db->obtenerConexion();
                
                $stmt = $conexion->query("
                    SELECT p.*, tp.nombre as tipo_nombre 
                    FROM productos p 
                    JOIN tipos_productos tp ON p.id_tipo = tp.id 
                    ORDER BY p.fecha_creacion DESC 
                    LIMIT 6
                ");
                $productos = $stmt->fetchAll();
                
                foreach ($productos as $producto):
                ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 sombra-card">
                        <div class="card-body text-center">
                            <i class="fas fa-shopping-bag icono-producto mb-3"></i>
                            <h5 class="card-title"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                            <p class="card-text texto-secundario"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                            <p class="precio-producto">$<?php echo number_format($producto['precio'], 2); ?></p>
                            <span class="badge bg-secundario"><?php echo htmlspecialchars($producto['tipo_nombre']); ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="categorias" class="py-5 bg-claro">
        <div class="container">
            <h2 class="text-center mb-5 titulo-seccion">Nuestras Categorías</h2>
            <div class="row">
                <?php
                $stmt = $conexion->query("SELECT * FROM tipos_productos ORDER BY nombre");
                $categorias = $stmt->fetchAll();
                
                foreach ($categorias as $categoria):
                ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 sombra-card">
                        <div class="card-body text-center">
                            <i class="fas fa-tags icono-categoria mb-3"></i>
                            <h5 class="card-title"><?php echo htmlspecialchars($categoria['nombre']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($categoria['descripcion']); ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="contacto" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5 titulo-seccion">Contacto</h2>
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="card sombra-card">
                        <div class="card-body p-5">
                            <div class="row text-center">
                                <div class="col-md-4 mb-3">
                                    <i class="fas fa-map-marker-alt icono-contacto mb-2"></i>
                                    <h5>Dirección</h5>
                                    <p class="texto-secundario">Av. Principal 123<br>Ciudad, País</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <i class="fas fa-phone icono-contacto mb-2"></i>
                                    <h5>Teléfono</h5>
                                    <p class="texto-secundario">+1 (555) 123-4567</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <i class="fas fa-envelope icono-contacto mb-2"></i>
                                    <h5>Email</h5>
                                    <p class="texto-secundario">info@sportmax.com</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-oscuro text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-dumbbell me-2"></i>Sport Max</h5>
                    <p class="mb-0">Tu tienda deportiva de confianza</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">&copy; 2024 Sport Max. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
