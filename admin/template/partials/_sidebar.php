<!-- partial:partials/_sidebar.html -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <div class="nav-link">
                <div class="profile-image">
                    <img src="images/faces/face1.jpg" alt="profile"/>
                </div>
                <div class="profile-name">
                    <p class="name">
                        <?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Administrador'; ?>
                    </p>
                    <p class="designation">
                        Administrador
                    </p>
                </div>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../template/index.php">
                <i class="fas fa-tachometer-alt menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../modules/reportajes.php">
                <i class="fas fa-file-alt menu-icon"></i>
                <span class="menu-title">Reportajes</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../modules/noticias.php">
                <i class="fas fa-newspaper menu-icon"></i>
                <span class="menu-title">Noticias</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../modules/boletines.php">
                <i class="fas fa-file-pdf menu-icon"></i>
                <span class="menu-title">Boletines</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../modules/usuarios.php">
                <i class="fas fa-users menu-icon"></i>
                <span class="menu-title">Usuarios</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-cog menu-icon"></i>
                <span class="menu-title">Configuración</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../logout.php">
                <i class="fas fa-sign-out-alt menu-icon"></i>
                <span class="menu-title">Cerrar Sesión</span>
            </a>
        </li>
    </ul>
</nav>
<!-- partial -->