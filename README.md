
# Sport Max - Tienda Deportiva

Sistema web simplificado para gestión de tienda deportiva desarrollado en PHP con Bootstrap.

## Características

- **Página principal** con diseño moderno y responsive
- **Sistema de login** basado en roles (admin/usuario)
- **Páginas individuales** para gestión:
  - Productos
  - Tipos de productos
- **Diseño responsive** con Bootstrap 5
- **Interfaz en español**

## Requisitos

- XAMPP (Apache + MySQL + PHP)
- PHP 7.4 o superior
- MySQL 5.7 o superior

## Instalación

1. **Clonar/Descargar** el proyecto en la carpeta `C:\xampp\htdocs\Calidad\`

2. **Iniciar XAMPP** y asegurarse de que Apache y MySQL estén ejecutándose

3. **Crear la base de datos**:
   - Abrir phpMyAdmin (http://localhost/phpmyadmin)
   - Importar el archivo `database.sql`

4. **Configurar la conexión** (si es necesario):
   - Editar `php/config.php` si tienes credenciales diferentes de MySQL

## Usuarios por defecto

- **Administrador**: 
  - Usuario: `admin`
  - Contraseña: `admin123`

- **Usuario normal**:
  - Usuario: `usuario`
  - Contraseña: `usuario123`

## Estructura del proyecto

```
Calidad/
├── css/
│   └── estilos.css              # Estilos personalizados
├── php/
│   ├── config.php               # Configuración de BD y funciones
│   ├── login.php                # Página de login
│   ├── logout.php               # Cerrar sesión
│   ├── dashboard.php            # Panel principal
│   ├── gestion_productos.php    # Gestión de productos
│   └── gestion_tipos.php        # Gestión de tipos
├── index.php                    # Página principal
└── database.sql                 # Script de base de datos
```

## Funcionalidades

### Página Principal (index.php)
- Hero section con información de la tienda
- Productos destacados
- Categorías de productos
- Información de contacto
- Navegación con login/logout

### Dashboard (dashboard.php)
- Panel principal con enlaces a las páginas de gestión
- Navegación intuitiva
- Información del sistema

### Gestión de Productos (gestion_productos.php)
- Crear, editar, eliminar productos
- Asignar tipo y categoría
- Control de stock y precios
- Validación completa de datos

### Gestión de Tipos (gestion_tipos.php)
- Crear, editar, eliminar tipos de productos
- Validación de productos asociados
- Protección contra eliminación accidental

## Tecnologías utilizadas

- **Backend**: PHP 7.4+
- **Base de datos**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Framework CSS**: Bootstrap 5.3.0
- **Iconos**: Font Awesome 6.0.0

## Seguridad

- Contraseñas encriptadas con MD5
- Validación de roles de usuario
- Sanitización de datos de entrada
- Protección contra inyección SQL con PDO

---

**Sport Max** - Tu tienda deportiva de confianza

