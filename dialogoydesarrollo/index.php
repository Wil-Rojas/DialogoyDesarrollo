<!--
Author: W3layouts
Author URL: http://w3layouts.com
-->
<!doctype html>
<html lang="en">
  
<!-- Mirrored from www.dialogoydesarrollo.com.pe/ by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 03 Sep 2026 16:50:04 GMT -->
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>DDP Noticias - Diálogo y Desarrollo Perú</title>

    <!-- Google fonts -->
    
	<link href="https://fonts.googleapis.com/css?family=Cabin:400,500,600&amp;subset=latin-ext,vietnamese" rel="stylesheet">
    
    <!-- Template CSS -->
    <link rel="stylesheet" href="assets/css/style-starter.css">
  </head>
  <body>
<!-- header -->
<header id="site-header" class="fixed-top">
  <div class="container">
      <nav class="navbar navbar-expand-lg stroke">
          <!--<a class="navbar-brand" href="index.html">
              <span class="fa fa-video-camera"></span> V-Conference
          </a>
           if logo is image enable this   -->
      <a class="navbar-brand" href="#index.html">
          <img src="assets/images/logo.png" alt="Your logo" title="Your logo" style="height:75px;" />
      </a> 
          <button class="navbar-toggler  collapsed bg-gradient" type="button" data-toggle="collapse"
              data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false"
              aria-label="Toggle navigation">
              <span class="navbar-toggler-icon fa icon-expand fa-bars"></span>
              <span class="navbar-toggler-icon fa icon-close fa-times"></span>
              </span>
          </button>

          <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
              <ul class="navbar-nav ml-auto">
                  <li class="nav-item active">
                      <a class="nav-link" href="index-2.php">Inicio <span class="sr-only">(current)</span></a>
                  </li>
                  <li class="nav-item @@about__active">
                      <a class="nav-link" href="#actualidad">Actualidad</a>
                  </li>
				  <li class="nav-item @@about__active">
                      <a class="nav-link" href="reportajes-1.php">Reportajes</a>
                  </li>
				  <li class="nav-item @@about__active">
                      <a class="nav-link" href="about.html">Podcast</a>
                  </li>
				  <li class="nav-item @@about__active">
                      <a class="nav-link" href="boletines.html">Boletín NTEP</a>
                  </li>
				  <li class="nav-item @@about__active">
                      <a class="nav-link" href="about.html">Alianzas</a>
                  </li>
                  <li class="nav-item @@contact__active">
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
<!-- //header -->

<?php require_once __DIR__ . '/../admin/config/conexion.php'; ?>

<section class="breadcrumb-area py-sm-5 py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb-contents">
                    <h2 class="title-big">Reportajes</h2>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="w3l-video w3l-homeblock3 " id="video">
    <div class="container-fluid">
        <div class="video-grids-info row">
            <div class="video-gd-right col-lg-6 p-0">
                <div class="position-relative">
                    <a href="mas-de-730-mineros-con-reinfo-vigente-o-suspendido-participan-en-las-elecciones-regionales-y-municipales.html"><img src="assets/images/video.jpg" alt="" class="img-fluid"></a>
                    <a href="#small-dialog" class="popup-with-zoom-anim play-view text-center position-absolute">
                    </a>
                    <div id="small-dialog" class="zoom-anim-dialog mfp-hide">
                        <iframe src="#" allow="autoplay; fullscreen"allowfullscreen=""></iframe>
                    </div>
                </div>
            </div>
            <div class="video-gd-left col-lg-6 p-lg-5 p-4 align-self">
                <div class="p-xl-4 p-0 video-wrap">
                    <h5>Ago 28, 2026</h5>
					<h3 class="title-big text-left mb-4"><a href="mas-de-730-mineros-con-reinfo-vigente-o-suspendido-participan-en-las-elecciones-regionales-y-municipales.html">Más de 730 mineros con Reinfo vigente o suspendido participan en las elecciones regionales y municipales</a></h3>
                    <p>43 candidatos buscan llegar a gobiernos regionales y 692 postulan a alcaldías y regidurías. El analista Iván Arenas advierte los posibles conflictos de interés y el riesgo de que estas autoridades favorezcan las actividades mineras informales...</p>
					<a href="mas-de-730-mineros-con-reinfo-vigente-o-suspendido-participan-en-las-elecciones-regionales-y-municipales.html" class="btn mt-4 p-0">Leer <span class="fa fa-arrow-right"></span> </a>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="grids-block-5 py-1">
    <section class="py-lg-4 py-md-3">
        <div class="container">
            <div class="row">
                <?php
                $sql_reportajes = "SELECT * FROM reportajes ORDER BY fecha_publicacion DESC LIMIT 3";
                $result_reportajes = $conexion->query($sql_reportajes);
                if ($result_reportajes->num_rows > 0) {
                    while($reportaje = $result_reportajes->fetch_assoc()) {
                        $titulo = htmlspecialchars($reportaje['titulo']);
                        $fecha = date('M j, Y', strtotime($reportaje['fecha_publicacion']));
                        $imagen = $reportaje['foto_principal'] ?: 'assets/images/default-reportaje.jpg';
                        $link = 'reportaje-' . $reportaje['id'] . '.php';
                ?>
                <div class="col-lg-4 col-md-6 grids5-info mt-5">
                    <a href="<?= $link ?>" class="d-block">
                        <img src="<?= $imagen ?>" alt="<?= $titulo ?>" class="img-fluid" />
                    </a>
                    <div class="blog-info">
                        <h5><?= $fecha ?></h5>
                        <h4><a href="<?= $link ?>" class="d-block"><?= $titulo ?></a></h4>
                        <a href="<?= $link ?>" class="btn mt-4 p-0">Leer <span class="fa fa-arrow-right"></span></a>
                    </div>
                </div>
                <?php }
                } else {
                    echo '<p class="text-center col-12">No hay reportajes disponibles.</p>';
                } ?>
            </div>
            <div class="pagination">
                <ul>
                    <li><a href="reportajes-1.html">Ver todos</a></li>
                </ul>
            </div>
        </div>
    </section>
</div>

<section class="breadcrumb-area py-sm-5 py-1">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb-contents">
                    <h2 class="title-big">Noticias Recientes</h2>
                    <a class="anchor" id="actualidad"></a>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="grids-block-5 py-5">
    <section class="py-lg-4 py-md-3">
        <div class="container">
            <div class="row">
                <?php
                $sql_noticias = "SELECT * FROM noticias ORDER BY fecha_publicacion DESC LIMIT 3";
                $result_noticias = $conexion->query($sql_noticias);
                if ($result_noticias->num_rows > 0) {
                    $i = 0;
                    while($noticia = $result_noticias->fetch_assoc()) {
                        $titulo = htmlspecialchars($noticia['titulo']);
                        $fecha = date('F j, Y', strtotime($noticia['fecha_publicacion']));
                        $imagen = $noticia['foto'] ?: 'assets/images/default-noticia.jpg';
                        $link = $noticia['link_externo'] ?: '#';
                        $target = $noticia['link_externo'] ? '_blank' : '_self';
                        $clase_mt = ($i > 0) ? ' mt-lg-0 mt-5' : '';
                        $i++;
                ?>
                <div class="col-lg-4 col-md-6 grids5-info<?= $clase_mt ?>">
                    <a target="<?= $target ?>" href="<?= $link ?>" class="d-block">
                        <img src="<?= $imagen ?>" alt="<?= $titulo ?>" class="img-fluid" />
                    </a>
                    <div class="blog-info">
                        <h5><?= $fecha ?></h5>
                        <h4><a target="<?= $target ?>" href="<?= $link ?>" class="d-block"><?= $titulo ?></a></h4>
                        <a target="<?= $target ?>" href="<?= $link ?>" class="btn mt-4 p-0">Leer <span class="fa fa-arrow-right"></span></a>
                    </div>
                </div>
                <?php }
                } else {
                    echo '<p class="text-center col-12">No hay noticias disponibles.</p>';
                } ?>
            </div>
            <div class="pagination">
                <ul>
                    <li><a target="_blank" href="https://www.facebook.com/DialogoyDesarrolloPeru">Ver todos</a></li>
                </ul>
            </div>
        </div>
    </section>
</div>

<section class="w3l-homeblock5 py-0">
    <div class="container py-lg-5 py-4">
        <div class="row">
            <div class="col-lg-8 align-self">
                <h3 class="title-big mb-4">Boletín NTEP <?= date('Y') ?></h3>
                <?php
                $sql_boletin = "SELECT * FROM boletines ORDER BY fecha_publicacion DESC LIMIT 1";
                $result_boletin = $conexion->query($sql_boletin);
                if ($result_boletin->num_rows > 0) {
                    $boletin = $result_boletin->fetch_assoc();
                    $resumen = $boletin['resumen'] ? nl2br(htmlspecialchars($boletin['resumen'])) : 'Descarga el último boletín informativo.';
                    $numero = $boletin['numero_boletin'];
                    $fecha = date('d F Y', strtotime($boletin['fecha_publicacion']));
                    $pdf_url = $boletin['archivo_pdf'] ?: '#';
                    $imagen = $boletin['foto_portada'] ?: 'assets/images/boletin-default.png';
                ?>
                <p><?= $resumen ?></p>
                <div class="row mt-sm-4 mt-2 px-3">
                    <div class="col-6 p-0">
                        <span><?= $numero ?></span>
                        <h4><?= $fecha ?></h4>
                    </div>
                    <div class="col-6 p-0">
                        <span><a target="_blank" href="<?= $pdf_url ?>" class="facebook"><span class="fa fa-download"></span></a></span>
                        <h4>Ver Boletín</h4>
                    </div>
                <?php } else { ?>
                <p>Próximamente nuevos boletines.</p>
                <?php } ?>
                    <center><a href="boletines.html" class="btn btn-style btn-primary mt-md-5 mt-4">Ver todos</a></center>
                </div>
            </div>
            <div class="col-lg-4 mt-lg-0 mt-4">
                <img src="<?= isset($imagen) ? $imagen : 'assets/images/boletin-default.png' ?>" class="img-fluid radius-image" alt="Boletín NTEP">
            </div>
        </div>
    </div>
</section>

<section class="w3l-homeblock3 py-5">
    <div class="container py-lg-5 py-md-4">
        <h3 class="title-big mb-5 text-center">Podcast</h3>
        <div class="row">
            <div class="col-lg-3 col-sm-6">
                <div class="area-box">
                    <img src="assets/images/podcast.png">
                    <p>Aumentan casos de hackeo de WhatsApp y delitos informáticos en el país.</p>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mt-sm-0 mt-5">
                <div class="area-box">
                    <img src="assets/images/podcast.png">
                    <p>Ministerio Público exige mayor presupuesto para la lucha contra las extorsiones.</p>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mt-lg-0 mt-5">
                <div class="area-box">
                    <img src="assets/images/podcast.png">
                    <p>Vivamus a ligula quam. elit leo blandit sed eu  non ipsum dolor, sed dolor amet laoreet.</p>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mt-lg-0 mt-5">
                <div class="area-box">
                    <img src="assets/images/podcast.png">
                    <p>Vivamus a ligula quam. elit leo blandit sed eu  non ipsum dolor, sed dolor amet laoreet.</p>
                </div>
            </div>
        </div>
		<center><a href="#btn" class="btn btn-style btn-primary mt-md-5 mt-4">Ver todos</a></center>
    </div>
</section>

<section class="w3l-team" id="team">
	<div class="teams1 py-5 mb-3">
		<div class="container py-lg-3 pb-lg-5 pb-4">
			<div class="teams1-content">
                <h3 class="title-big text-center mb-5">Especiales</h3>
					<div class="owl-carousel owl-theme text-center">
						<div class="item">
							<div class="d-grid team-info">
								<div class="column position-relative">
									<a href="#url"><img src="assets/images/team2.jpg" alt="" class="img-fluid rounded team-image" /></a>
								</div>
								<div class="column">
									<p>Por una mineria artesanal segura para todos</p>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="d-grid team-info">
								<div class="column position-relative">
									<a href="#url"><img src="assets/images/team3.jpg" alt="" class="img-fluid rounded team-image" /></a>
								</div>
								<div class="column">
									<p>REINFO Días decisivos en el Congreso</p>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="d-grid team-info">
								<div class="column position-relative">
									<a href="#url"><img src="assets/images/team4.jpg" alt="" class="img-fluid rounded team-image" /></a>
								</div>
								<div class="column">
									<p>La minería ilegal: un negocio rentable para bandas criminales</p>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="d-grid team-info">
								<div class="column position-relative">
									<a href="#url"><img src="assets/images/team5.jpg" alt="" class="img-fluid rounded team-image" /></a>
								</div>
								<div class="column">
									<p>El problema del REINFO y la minería ilegal en 50 segundos</p>
								</div>
							</div>
						</div>
					</div>
			</div>
		</div>
	</div>
</section>

<section class="w3l-banner py-0" id="work">
    <div class="midd-w3 py-lg-4 py-md-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mt-lg-0 mt-lg-5 about-right-faq align-self">
                    <h5 class="title-small mb-2">DDP Noticias</h5>
                    <h3 class="title-banner">Diálogo y Desarrollo Perú</h3>
                    <p class="mt-4">Somos un espacio de periodismo independiente que busca visibilizar las acciones de diálogo en el país desde una mirada constructiva.</p>
                        <a href="#btn" class="btn btn-style btn-primary mt-md-5 mt-4">Nosotros</a>
                 </div>
                <div class="col-md-6 left-wthree-img mt-lg-0 mt-4">
                    <div class="position-relative">
                        <img src="assets/images/bannerimg.jpg" alt="" class="img-fluid">
                        <div id="small-dialog" class="zoom-anim-dialog mfp-hide">
                            <iframe src="https://www.youtube.com/embed/2jI6fHBtRJU" allow="autoplay; fullscreen" allowfullscreen=""></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="middle py-5">
    <div class="container py-xl-5 py-lg-3">
        <div class="welcome-left text-center py-md-5 py-3">
            <h3 class="title-big">Síguenos en nuestras Redes Sociales</h3>
            <div class="main-social-footer-29">
            <a target="_blank" href="https://www.facebook.com/DialogoyDesarrolloPeru" class="facebook"><span class="fa fa-facebook-square fa-2x"></span></a>
            <a target="_blank" href="https://www.tiktok.com/@dialogo.y.desarrollo" class="twitter"><img src="assets/images/tiktokg.png"></a>
            <a target="_blank" href="https://www.instagram.com/dialogo.y.desarrollo/" class="instagram"><span class="fa fa-instagram fa-2x"></span></a>
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
            <li><a href="#url">Noticias</a></li>
            <li><a href="#url">Videos</a></li>
            <li><a href="#url">Posdcast.</a></li>
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
    window.onscroll = function () {
      scrollFunction()
    };
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
<script type="text/javascript">
  $(document).ready(function () {
    $('#parentHorizontalTab').easyResponsiveTabs({
      type: 'default',
      width: 'auto',
      fit: true,
      tabidentify: 'hor_1',
      activate: function (event) {
        var $tab = $(this);
        var $info = $('#nested-tabInfo');
        var $name = $('span', $info);
        $name.text($tab.text());
        $info.show();
      }
    });
  });
</script>

<script src="assets/js/owl.carousel.js"></script>
<script>
  $(document).ready(function () {
    $('.owl-logos').owlCarousel({
      loop: true,
      margin: 0,
      nav: false,
      responsiveClass: true,
      autoplay: true,
      autoplayTimeout: 5000,
      autoplaySpeed: 1000,
      autoplayHoverPause: false,
      responsive: {
        0: {
          items: 2,
          nav: false
        },
        480: {
          items: 2,
          nav: false
        },
        568: {
          items: 3,
          nav: false
        },
        1000: {
          items: 5,
          nav: false
        }
      }
    })
  })
</script>

<script>
  $(document).ready(function () {
    $("#owl-demo1").owlCarousel({
      loop: true,
      margin: 20,
      responsiveClass: true,
      responsive: {
        0: {
          items: 1,
          nav: true
        },
        768: {
          items: 2,
          nav: false
        },
        1000: {
          items: 3,
          nav: true,
          loop: false
        }
      }
    })
  })
</script>

<script>
  $(document).ready(function () {
    $('.owl-carousel').owlCarousel({
      loop: true,
      margin: 0,
      responsiveClass: true,
      responsive: {
        0: {
          items: 1,
          nav: true
        },
        400: {
          items: 2,
          nav: true,
          margin: 20
        },
        768: {
          items: 3,
          nav: true,
          margin: 20
        },
        1000: {
          items: 4,
          nav: true,
          loop: true,
          margin: 25
        }
      }
    })
  })
</script>

<script>
  (() => {
    const deadlineDate = new Date('January 27, 2025 23:59:59').getTime();
    const countdownDays = document.querySelector('.countdown__days .number');
    const countdownHours = document.querySelector('.countdown__hours .number');
    const countdownMinutes = document.querySelector('.countdown__minutes .number');
    const countdownSeconds = document.querySelector('.countdown__seconds .number');
    setInterval(() => {
      const currentDate = new Date().getTime();
      const distance = deadlineDate - currentDate;
      const days = Math.floor(distance / (1000 * 60 * 60 * 24));
      const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      const seconds = Math.floor((distance % (1000 * 60)) / 1000);
      countdownDays.innerHTML = days;
      countdownHours.innerHTML = hours;
      countdownMinutes.innerHTML = minutes;
      countdownSeconds.innerHTML = seconds;
    }, 1000);
  })();
</script>

<script src="assets/js/jquery.magnific-popup.min.js"></script>
<script>
  $(document).ready(function () {
    $('.popup-with-zoom-anim').magnificPopup({
      type: 'inline',
      fixedContentPos: false,
      fixedBgPos: true,
      overflowY: 'auto',
      closeBtnInside: true,
      preloader: false,
      midClick: true,
      removalDelay: 300,
      mainClass: 'my-mfp-zoom-in'
    });
    $('.popup-with-move-anim').magnificPopup({
      type: 'inline',
      fixedContentPos: false,
      fixedBgPos: true,
      overflowY: 'auto',
      closeBtnInside: true,
      preloader: false,
      midClick: true,
      removalDelay: 300,
      mainClass: 'my-mfp-slide-bottom'
    });
  });
</script>

<script>
  $(function () {
    $('.navbar-toggler').click(function () {
      $('body').toggleClass('noscroll');
    })
  });
</script>

<script>
  $(window).on("scroll", function () {
    var scroll = $(window).scrollTop();
    if (scroll >= 80) {
      $("#site-header").addClass("nav-fixed");
    } else {
      $("#site-header").removeClass("nav-fixed");
    }
  });
  $(".navbar-toggler").on("click", function () {
    $("header").toggleClass("active");
  });
  $(document).on("ready", function () {
    if ($(window).width() > 991) {
      $("header").removeClass("active");
    }
    $(window).on("resize", function () {
      if ($(window).width() > 991) {
        $("header").removeClass("active");
      }
    });
  });
</script>

<script src="assets/js/bootstrap.min.js"></script>

</body>
</html>