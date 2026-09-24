# Diálogo y Desarrollo Perú — Plataforma Web

Sitio web informativo de la organización Diálogo y Desarrollo Perú con panel de administración para la gestión de contenido (reportajes, noticias y boletines). Desarrollado en PHP + MySQL y desplegado con integración continua a través de GitHub Actions.

## Demo en vivo

- Sitio público: http://dialogoydesarrolloproyecto.ifree.page
- Panel admin: http://dialogoydesarrolloproyecto.ifree.page/admin/

- usuario: admin@dialogoydesarrollo.pe
- contraseña: 1234 (encriptada)

## Tecnologías

- Backend: PHP 8.2
- Base de datos: MySQL
- Frontend: HTML5, CSS3, JavaScript, Bootstrap
- Control de versiones: Git + GitHub
- Hosting: InfinityFree
- CI/CD: GitHub Actions + FTP-Deploy-Action

## Estructura del proyecto

DD/
├── admin/              # Panel de administración
│   ├── config/         # Conexión a base de datos
│   ├── modules/        # Módulos CRUD (reportajes, noticias, etc.)
│   └── template/       # Plantilla del dashboard
├── assets/             # Recursos estáticos (CSS, JS, imágenes)
├── boletines/          # PDFs de boletines
├── .github/workflows/  # Configuración de deploy automático
├── index.php           # Página principal
├── reportajes-1.php    # Listado de reportajes
├── reportaje.php       # Detalle de reportaje
└── config/             # Configuración general

## Instalación local

1. Clona el repositorio:
   git clone https://github.com/Wil-Rojas/DialogoyDesarrollo.git

2. Mueve el proyecto a tu servidor local (XAMPP, Laragon, etc.):
   C:\xampp\htdocs\DD\

3. Importa la base de datos:
   - Abre phpMyAdmin
   - Crea una base de datos llamada revista_digital
   - Importa el archivo dialogoydesarrollo.sql

4. Configura la conexión en admin/config/conexion.php:
   $host = 'localhost';
   $user = 'root';
   $password = '';
   $database = 'revista_digital';

5. Abre el navegador en http://localhost/DD/

## Acceso al panel de administración

- URL: /admin/
- Usuario: (configurado en la base de datos)
- Contraseña: (cifrada con password_hash de PHP)

## Deploy automático

Cada git push a la rama main ejecuta el workflow de GitHub Actions definido en .github/workflows/deploy.yml, que sincroniza los archivos por FTP al hosting InfinityFree.

Secrets necesarios en GitHub:
- FTP_USERNAME
- FTP_PASSWORD

## Licencia

Proyecto académico — Curso Plataformas para el Desarrollo de Aplicaciones
Universidad Andina del Cusco — 2026-II