-- Base de datos para Sport Max
CREATE DATABASE IF NOT EXISTS sportmax;
USE sportmax;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'usuario') NOT NULL DEFAULT 'usuario',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de tipos de productos
CREATE TABLE tipos_productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de productos
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    imagen VARCHAR(255),
    id_tipo INT NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_tipo) REFERENCES tipos_productos(id) ON DELETE CASCADE
);

-- Insertar usuario administrador por defecto
INSERT INTO usuarios (usuario, contrasena, rol) VALUES 
('admin', MD5('admin123'), 'admin'),
('usuario', MD5('usuario123'), 'usuario');

-- Insertar algunos tipos de productos de ejemplo
INSERT INTO tipos_productos (nombre, descripcion) VALUES 
('Fútbol', 'Productos relacionados con fútbol'),
('Básquetbol', 'Productos relacionados con básquetbol'),
('Tenis', 'Productos relacionados con tenis'),
('Fitness', 'Productos para entrenamiento y fitness'),
('Running', 'Productos para correr y atletismo');

-- Insertar algunos productos de ejemplo
INSERT INTO productos (nombre, descripcion, precio, stock, id_tipo) VALUES 
('Balón de Fútbol Nike', 'Balón oficial de fútbol Nike, tamaño 5', 45.99, 50, 1),
('Zapatillas Adidas Running', 'Zapatillas deportivas para correr', 89.99, 30, 5),
('Raqueta de Tenis Wilson', 'Raqueta profesional de tenis', 120.00, 15, 3),
('Pelota de Básquet Spalding', 'Pelota oficial de básquetbol', 35.50, 40, 2),
('Mancuernas Ajustables', 'Set de mancuernas ajustables 2x20kg', 75.00, 25, 4);
