<?php
require_once 'config.php';

$error = '';

if ($_POST) {
    $usuario = $_POST['usuario'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';
    
    if (!empty($usuario) && !empty($contrasena)) {
        $db = ConexionDB::obtenerInstancia();
        $conexion = $db->obtenerConexion();
        
        $stmt = $conexion->prepare("SELECT id, usuario, contrasena, rol FROM usuarios WHERE usuario = ?");
        $stmt->execute([$usuario]);
        $usuario_data = $stmt->fetch();
        
        if ($usuario_data && md5($contrasena) === $usuario_data['contrasena']) {
            $_SESSION['usuario_id'] = $usuario_data['id'];
            $_SESSION['usuario'] = $usuario_data['usuario'];
            $_SESSION['rol'] = $usuario_data['rol'];
            
            if ($usuario_data['rol'] === 'admin') {
                header('Location: dashboard.php');
            } else {
                header('Location: index.php');
            }
            exit();
        } else {
            $error = 'Usuario o contraseña incorrectos';
        }
    } else {
        $error = 'Por favor complete todos los campos';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sport Max</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/estilos.css" rel="stylesheet">
</head>
<body class="fondo-login">
    <div class="container">
        <div class="row justify-content-center min-vh-100 align-items-center">
            <div class="col-md-6 col-lg-4">
                <div class="card sombra-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h2 class="titulo-principal">Sport Max</h2>
                            <p class="texto-secundario">Iniciar Sesión</p>
                        </div>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label for="usuario" class="form-label">Usuario</label>
                                <input type="text" class="form-control" id="usuario" name="usuario" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="contrasena" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primario">Iniciar Sesión</button>
                            </div>
                        </form>
                        
                        <div class="text-center mt-3">
                            <small class="texto-secundario">
                                Usuarios de prueba:<br>
                                Admin: admin / admin123<br>
                                Usuario: usuario / usuario123
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
