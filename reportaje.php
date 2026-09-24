<?php
require_once __DIR__ . '/admin/config/conexion.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: reportajes-1.php");
    exit();
}

$sql = "SELECT r.*, a.nombres AS autor_nombre, a.ap_paterno AS autor_ap 
        FROM reportajes r 
        LEFT JOIN autores a ON r.autor_id = a.id 
        WHERE r.id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$reportaje = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$reportaje) {
    header("Location: reportajes-1.php");
    exit();
}

$titulo = htmlspecialchars($reportaje['titulo']);
$resumen = htmlspecialchars($reportaje['resumen_corto'] ?? '');
$desarrollo = $reportaje['desarrollo'];
$fecha = date('d \d\e F \d\e Y', strtotime($reportaje['fecha_publicacion']));
$imagen = $reportaje['foto_principal'] ?: 'assets/images/default-reportaje.jpg';
$pdf = $reportaje['pdf_adjunto'] ?? '';

// Fotos adicionales
$sql_fotos = "SELECT * FROM reportajes_fotos WHERE reportaje_id = ? ORDER BY orden ASC";
$stmt2 = $conexion->prepare($sql_fotos);
$stmt2->bind_param("i", $id);
$stmt2->execute();
$fotos_extra = $stmt2->get_result();
$stmt2->close();
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $titulo ?> - Diálogo y Desarrollo Perú</title>
    <link href="https://fonts.googleapis.com/css?family=Cabin:400,500,600&amp;subset=latin-ext,vietnamese" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style-starter.css">
</head>
<body>

<header id="site-header" class="fixed-top">
  <div class="container">
      <nav class="navbar navbar-expand-lg stroke">
      <a class="navbar-brand" href="index.php">
          <img src="assets/images/logo.png" alt="Logo" style="height:75px;" />
      </a> 
          <button class="navbar-toggler collapsed bg-gradient" type="button" data-toggle="collapse"
              data-target="#navbarTogglerDemo02">
              <span class="navbar-toggler-icon fa icon-expand fa-bars"></span>
              <span class="navbar-toggler-icon fa icon-close fa-times"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
              <ul class="navbar-nav ml-auto">
                  <li class="nav-item"><a class="nav-link" href="index-2.php">Inicio</a></li>
                  <li class="nav-item"><a class="nav-link" href="index-2.php#actualidad">Actualidad</a></li>
                  <li class="nav-item active"><a class="nav-link" href="reportajes-1.php">Reportajes</a></li>
                  <li class="nav-item"><a class="nav-link" href="about.html">Podcast</a></li>
                  <li class="nav-item"><a class="nav-link" href="boletines.html">Boletín NTEP</a></li>
                  <li class="nav-item"><a class="nav-link" href="contact.html">Sobre D&D</a></li>
                  <li class="ml-2"><a href="#btn" class="btn btn-style btn-outline-secondary">Contacto</a></li>
              </ul>
          </div>
      </nav>
  </div>
</header>

<section class="breadcrumb-area py-sm-5 py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb-contents">
                    <h2 class="title-big"><?= $titulo ?></h2>
                    <div class="breadcrumb">
                        <ul>
                            <li><a href="index-2.php">Inicio</a></li>
                            <li><a href="reportajes-1.php">Reportajes</a></li>
                            <li class="active"><?= $titulo ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="w3l-blog-single py-5">
    <div class="container py-lg-5 py-md-4">
        <div class="row">
            <div class="col-lg-8">
                <div class="blog-single-wrap">
                    <img src="<?= $imagen ?>" alt="<?= $titulo ?>" class="img-fluid radius-image mb-4">
                    
                    <h5 class="mb-3"><?= $fecha ?></h5>
                    
                    <?php if ($resumen): ?>
                        <p class="lead"><strong><?= $resumen ?></strong></p>
                    <?php endif; ?>
                    
                    <div class="content">
                        <?= $desarrollo ?>
                    </div>
                    
                    <?php if ($pdf): ?>
                        <div class="mt-5">
                            <a href="<?= $pdf ?>" target="_blank" class="btn btn-style btn-primary">
                                <span class="fa fa-file-pdf"></span> Descargar PDF adjunto
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($fotos_extra->num_rows > 0): ?>
                        <div class="row mt-5">
                            <div class="col-12"><h4 class="mb-4">Galería de imágenes</h4></div>
                            <?php while($foto = $fotos_extra->fetch_assoc()): ?>
                                <div class="col-md-6 mb-4">
                                    <img src="<?= $foto['url_foto'] ?>" alt="<?= htmlspecialchars($foto['descripcion'] ?? '') ?>" class="img-fluid radius-image">
                                    <?php if ($foto['descripcion']): ?>
                                        <p class="text-muted mt-2"><?= htmlspecialchars($foto['descripcion']) ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mt-5">
                        <a href="reportajes-1.php" class="btn btn-style btn-outline-secondary">
                            <span class="fa fa-arrow-left"></span> Volver a reportajes
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 mt-lg-0 mt-5">
                <div class="sidebar-widget">
                    <h4 class="mb-4">Otros reportajes</h4>
                    <?php
                    $otros = $conexion->query("SELECT id, titulo, foto_principal, fecha_publicacion FROM reportajes WHERE id != $id ORDER BY fecha_publicacion DESC LIMIT 5");
                    while($otro = $otros->fetch_assoc()):
                    ?>
                        <div class="media mb-3">
                            <img src="<?= $otro['foto_principal'] ?: 'assets/images/default-reportaje.jpg' ?>" width="80" height="80" style="object-fit:cover;" class="mr-3">
                            <div class="media-body">
                                <h6 class="mt-0">
                                    <a href="reportaje.php?id=<?= $otro['id'] ?>">
                                        <?= htmlspecialchars(substr($otro['titulo'], 0, 50)) ?>...
                                    </a>
                                </h6>
                                <small class="text-muted"><?= date('d/m/Y', strtotime($otro['fecha_publicacion'])) ?></small>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="w3l-footer-29-main py-5" id="footer">
  <div class="footer-29 py-md-3">
    <div class="container">
      <div class="row footer-top-29">
        <div class="col-lg-6 col-md-6 footer-list-29 footer-1">
          <h6 class="footer-title-29">Quiénes Somos</h6>
          <p>Somos un espacio de periodismo independiente que busca visibilizar las acciones de diálogo en el país desde una mirada constructiva.</p>
          <div class="main-social-footer-29">
            <a target="_blank" href="https://www.facebook.com/DialogoyDesarrolloPeru" class="facebook"><span class="fa fa-facebook-square"></span></a>
            <a target="_blank" href="https://www.tiktok.com/@dialogo.y.desarrollo" class="twitter"><img src="assets/images/tiktokp.png"></a>
            <a target="_blank" href="https://www.instagram.com/dialogo.y.desarrollo/" class="instagram"><span class="fa fa-instagram"></span></a>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 footer-list-29 footer-2 mt-md-0 mt-5">
          <ul>
            <h6 class="footer-title-29">Contenido</h6>
            <li><a href="reportajes-1.php">Reportajes</a></li>
            <li><a href="#url">Videos</a></li>
            <li><a href="#url">Podcast</a></li>
          </ul>
        </div>
        <div class="col-lg-3 col-md-6 mt-lg-0 mt-5 footer-list-29 footer-3">
          <div class="properties">
            <h6 class="footer-title-29">Contacto</h6>
            <ul>
              <li><a href="#url">info@dialogoydesarrollo.com.pe</a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="bottom-copies text-center">
        <p class="copy-footer-29">© 2026 Diálogo y Desarrollo Perú. All rights reserved | Designed by <a target="_blank" href="https://www.wsperu.info/">WebSolutions</a></p>
      </div>
    </div>
  </div>
</footer>

<script src="assets/js/jquery-3.3.1.min.js"></script>
<script src="assets/js/theme-change.js"></script>
<script src="assets/js/bootstrap.min.js"></script>

</body>
</html>