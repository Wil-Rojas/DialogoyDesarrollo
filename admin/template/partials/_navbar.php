<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo" href="index.php">
            <i class="fas fa-newspaper" style="font-size: 1.5rem; color: #0d6efd;"></i>
            <span style="font-weight: bold; font-size: 1.2rem;">Diálogo y Desarrollo</span>
        </a>
        <a class="navbar-brand brand-logo-mini" href="index.php">
            <i class="fas fa-newspaper"></i>
        </a>
        <button class="navbar-toggler navbar-toggler align-self-center d-none d-lg-flex" type="button" data-toggle="minimize">
            <span class="typcn typcn-th-menu"></span>
        </button>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <ul class="navbar-nav mr-lg-2">
            <li class="nav-item d-none d-lg-flex">
                <a class="nav-link" href="index.php">Dashboard</a>
            </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
                <a class="nav-link dropdown-toggle pl-0 pr-0" href="#" data-toggle="dropdown" id="profileDropdown">
                    <i class="fas fa-user-circle" style="font-size: 1.5rem;"></i>
                    <span class="nav-profile-name"><?php echo $_SESSION['user_name']; ?></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                    <a class="dropdown-item" href="../">
                        <i class="fas fa-external-link-alt text-primary"></i> Ver Sitio
                    </a>
                    <a class="dropdown-item" href="../api/logout.php">
                        <i class="fas fa-sign-out-alt text-danger"></i> Cerrar Sesión
                    </a>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="typcn typcn-th-menu"></span>
        </button>
    </div>
</nav>