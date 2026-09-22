<?php
// Verificar sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// ✅ Ruta ABSOLUTA - NUNCA FALLA
require_once __DIR__ . '/../config/conexion.php';

// Obtener estadísticas
$total_reportajes = $conexion->query("SELECT COUNT(*) as total FROM reportajes")->fetch_assoc()['total'];
$total_noticias = $conexion->query("SELECT COUNT(*) as total FROM noticias")->fetch_assoc()['total'];
$total_boletines = $conexion->query("SELECT COUNT(*) as total FROM boletines")->fetch_assoc()['total'];
$total_usuarios = $conexion->query("SELECT COUNT(*) as total FROM usuarios")->fetch_assoc()['total'];

$ultimos_reportajes = $conexion->query("SELECT id, titulo, fecha_publicacion FROM reportajes ORDER BY fecha_publicacion DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <base href="/DD/admin/template/">  
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard - Diálogo y Desarrollo</title>
    <link rel="stylesheet" href="vendors/typicons.font/font/typicons.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="css/vertical-layout-light/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="shortcut icon" href="images/favicon.png" />
    <style>
        .card-stat {
            border-left: 4px solid #0d6efd;
            transition: all 0.3s;
        }
        .card-stat:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .card-stat .card-body i {
            font-size: 2.5rem;
            opacity: 0.6;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
        }
        .content-wrapper {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container-scroller">
        <?php include 'partials/_navbar.php'; ?>
        <div class="container-fluid page-body-wrapper">
            <?php include 'partials/_sidebar.php'; ?>
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-md-12 grid-margin">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="font-weight-bold">Bienvenido, <?php echo $_SESSION['user_name']; ?> 👋</h3>
                                <small class="text-muted">Panel de administración</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 grid-margin stretch-card">
                            <div class="card card-stat">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="card-title">Reportajes</h4>
                                            <h2 class="text-primary"><?php echo $total_reportajes; ?></h2>
                                            <p class="text-muted">Publicados</p>
                                        </div>
                                        <i class="fas fa-file-alt text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 grid-margin stretch-card">
                            <div class="card card-stat">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="card-title">Noticias</h4>
                                            <h2 class="text-success"><?php echo $total_noticias; ?></h2>
                                            <p class="text-muted">Publicadas</p>
                                        </div>
                                        <i class="fas fa-newspaper text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 grid-margin stretch-card">
                            <div class="card card-stat">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="card-title">Boletines</h4>
                                            <h2 class="text-info"><?php echo $total_boletines; ?></h2>
                                            <p class="text-muted">Publicados</p>
                                        </div>
                                        <i class="fas fa-file-pdf text-info"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 grid-margin stretch-card">
                            <div class="card card-stat">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="card-title">Usuarios</h4>
                                            <h2 class="text-warning"><?php echo $total_usuarios; ?></h2>
                                            <p class="text-muted">Registrados</p>
                                        </div>
                                        <i class="fas fa-users text-warning"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Acciones Rápidas</h4>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <a href="../modules/reportajes.php" class="btn btn-primary btn-block">
                                                <i class="fas fa-plus-circle"></i> Nuevo Reportaje
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="../modules/noticias.php" class="btn btn-success btn-block">
                                                <i class="fas fa-plus-circle"></i> Nueva Noticia
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="../modules/boletines.php" class="btn btn-info btn-block">
                                                <i class="fas fa-plus-circle"></i> Nuevo Boletín
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="../modules/usuarios.php?nuevo=1" class="btn btn-warning btn-block">
                                                <i class="fas fa-user-plus"></i> Nuevo Usuario
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Últimos Reportajes</h4>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Título</th>
                                                    <th>Fecha</th>
                                                    <th>Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while($row = $ultimos_reportajes->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars(substr($row['titulo'], 0, 60)) . (strlen($row['titulo']) > 60 ? '...' : ''); ?></td>
                                                    <td><?php echo date('d/m/Y', strtotime($row['fecha_publicacion'])); ?></td>
                                                    <td>
                                                        <a href="../modules/reportajes.php" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <?php include 'partials/_footer.php'; ?>
            </div>
        </div>
    </div>
    <script src="vendors/js/vendor.bundle.base.js"></script>
    <script src="js/off-canvas.js"></script>
    <script src="js/hoverable-collapse.js"></script>
    <script src="js/template.js"></script>
    <script src="js/settings.js"></script>
    <script src="js/todolist.js"></script>
</body>
</html>