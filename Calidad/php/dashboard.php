<?php
require_once 'config.php';
verificarAdmin();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sport Max</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../css/estilos.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primario">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="../index.php">
                <i class="fas fa-dumbbell me-2"></i>Sport Max - Dashboard
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="../index.php">
                    <i class="fas fa-home me-1"></i>Ir al Sitio
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
                            <a class="nav-link activo" href="dashboard.php">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="gestion_productos.php">
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
                    <h1 class="h2">Panel de Administración</h1>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 sombra-card">
                            <div class="card-body text-center">
                                <i class="fas fa-box fa-3x text-primario mb-3"></i>
                                <h4 class="card-title">Gestión de Productos</h4>
                                <p class="card-text">Administra el inventario de productos, precios, stock y categorías.</p>
                                <a href="gestion_productos.php" class="btn btn-primario">
                                    <i class="fas fa-cog me-2"></i>Gestionar Productos
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 sombra-card">
                            <div class="card-body text-center">
                                <i class="fas fa-tags fa-3x text-secundario mb-3"></i>
                                <h4 class="card-title">Gestión de Tipos</h4>
                                <p class="card-text">Crea y administra las categorías y tipos de productos.</p>
                                <a href="gestion_tipos.php" class="btn btn-secundario">
                                    <i class="fas fa-cog me-2"></i>Gestionar Tipos
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Información del Sistema</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <h6><i class="fas fa-database me-2"></i>Base de Datos</h6>
                                        <p class="text-muted">Sistema de gestión de inventario con MySQL</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h6><i class="fas fa-shield-alt me-2"></i>Seguridad</h6>
                                        <p class="text-muted">Acceso restringido solo para administradores</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h6><i class="fas fa-mobile-alt me-2"></i>Responsive</h6>
                                        <p class="text-muted">Interfaz adaptable a todos los dispositivos</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>