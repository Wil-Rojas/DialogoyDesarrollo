<!--
Author: W3layouts
Author URL: http://w3layouts.com
-->
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Reportajes - Diálogo y Desarrollo Perú</title>
    <link href="https://fonts.googleapis.com/css?family=Cabin:400,500,600&amp;subset=latin-ext,vietnamese" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style-starter.css">
</head>
<body>

<?php
require_once __DIR__ . '/admin/config/conexion.php'; 

$por_pagina = 9;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1;
$offset = ($pagina - 1) * $por_pagina;

$total_query = $conexion->query("SELECT COUNT(*) as total FROM reportajes");
$total_reportajes = $total_query->fetch_assoc()['total'];
$total_paginas = ceil($total_reportajes / $por_pagina);

$sql = "SELECT * FROM reportajes ORDER BY fecha_publicacion DESC LIMIT $por_pagina OFFSET $offset";
$reportajes = $conexion->query($sql);
?>

<header id="site-header" class="fixed-top">
  <div class="container">
      <nav class="navbar navbar-expand-lg stroke">
      <a class="navbar-brand" href="index.php">
          <img src="assets/images/logo.png" alt="Your logo" title="Your logo" style="height:75px;" />
      </a> 
          <button class="navbar-toggler collapsed bg-gradient" type="button" data-toggle="collapse"
              data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false"
              aria-label="Toggle navigation">
              <span class="navbar-toggler-icon fa icon-expand fa-bars"></span>
              <span class="navbar-toggler-icon fa icon-close fa-times"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
              <ul class="navbar-nav ml-auto">
                  <li class="nav-item">
                      <a class="nav-link" href="index-2.php">Inicio</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="index-2.php#actualidad">Actualidad</a>
                  </li>
				  <li class="nav-item active">
                      <a class="nav-link" href="reportajes-1.php">Reportajes</a>
                  </li>
				  <li class="nav-item">
                      <a class="nav-link" href="about.html">Podcast</a>
                  </li>
				  <li class="nav-item">
                      <a class="nav-link" href="boletines.html">Boletín NTEP</a>
                  </li>
				  <li class="nav-item">
                      <a class="nav-link" href="about.html">Alianzas</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="contact.html">Sobre D&D</a>
                  </li>				  
                  <li class="ml-2">
                      <a href="#btn" class="btn btn-style btn-outline-secondary">Contacto</a>
                  </li>
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
                    <h2 class="title-big">Reportajes</h2>
                    <div class="breadcrumb">
                        <ul>
                            <li><a href="index-2.php">Inicio</a></li>
                            <li class="active">Reportajes</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="grids-block-5 py-5">
    <section class="py-lg-4 py-md-3">
        <div class="container">
            <div class="row">
                <?php if ($reportajes->num_rows > 0): ?>
                    <?php while($reportaje = $reportajes->fetch_assoc()): 
                        $titulo = htmlspecialchars($reportaje['titulo']);
                        $fecha = date('M d, Y', strtotime($reportaje['fecha_publicacion']));
                        $imagen = $reportaje['foto_principal'] ? '../' . $reportaje['foto_principal'] : 'assets/images/default-reportaje.jpg';
                        $link = 'reportaje.php?id=' . $reportaje['id'];
                    ?>
                    <div class="col-lg-4 col-md-6 grids5-info mt-5">
                        <a href="<?= $link ?>" class="d-block">
                            <img src="<?= $imagen ?>" alt="<?= $titulo ?>" class="img-fluid" />
                        </a>
                        <div class="blog-info">
                            <h5><?= $fecha ?></h5>
                            <h4><a href="<?= $link ?>" class="d-block"><?= $titulo ?></a></h4>
                            <a href="<?= $link ?>" class="btn mt-4 p-0">Leer <span class="fa fa-arrow-right"></span> </a>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p>No hay reportajes disponibles.</p>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($total_paginas > 1): ?>
            <div class="pagination">
                <ul>
                    <?php if ($pagina > 1): ?>
                        <li class="prev"><a href="?pagina=<?= $pagina - 1 ?>"> Ant</a></li>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                        <li>
                            <a href="?pagina=<?= $i ?>" class="<?= ($i == $pagina) ? 'active' : '' ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($pagina < $total_paginas): ?>
                        <li class="next"><a href="?pagina=<?= $pagina + 1 ?>"> Sig </a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <?php endif; ?>
        </div>
    </section>
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
  <button onclick="topFunction()" id="movetop" title="Go to top">
    <span class="fa fa-angle-up"></span>
  </button>
  <script>
    window.onscroll = function () { scrollFunction() };
    function scrollFunction() {
      if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        document.getElementById("movetop").style.display = "block";
      } else {
        document.getElementById("movetop").style.display = "none";
      }
    }
    function topFunction() {
      document.body.scrollTop = 0;
      document.documentElement.scrollTop = 0;
    }
  </script>
</section>

<script src="assets/js/jquery-3.3.1.min.js"></script>
<script src="assets/js/theme-change.js"></script>
<script src="assets/js/easyResponsiveTabs.js"></script>
<script src="assets/js/owl.carousel.js"></script>
<script src="assets/js/jquery.magnific-popup.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>

<script>
  $(document).ready(function () {
    $('.owl-carousel').owlCarousel({
      loop: true, margin: 0, responsiveClass: true,
      responsive: {
        0: { items: 1, nav: true },
        400: { items: 2, nav: true, margin: 20 },
        768: { items: 3, nav: true, margin: 20 },
        1000: { items: 4, nav: true, loop: true, margin: 25 }
      }
    });
  });
</script>

</body>
</html>