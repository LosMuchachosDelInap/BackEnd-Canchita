<!-- Navbar responsive optimizado -->
<nav class="navbar navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <div class="row w-100 align-items-center">
      <!-- Columna izquierda: menú lateral -->
      <div class="col-3 col-sm-4 d-flex justify-content-start">
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="offcanvas"
          data-bs-target="#offcanvasDarkNavbar"
          aria-controls="offcanvasDarkNavbar"
          aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>
      <!-- Columna central: logo -->
      <div class="col-6 col-sm-4 d-flex justify-content-center">
        <a class="navbar-brand mx-auto" href="<?php echo BASE_URL; ?>/index.php">
          <figure class="m-0">
            <img src="<?php echo BASE_URL; ?>/src/Public/Logo.png" 
                 class="logo-navbar" 
                 width="45" 
                 height="45" 
                 alt="La canchita de los pibes"
                 loading="lazy">
          </figure>
        </a>
      </div>
      <!-- Columna derecha: botones usuario -->
      <div class="col-3 col-sm-4 d-flex justify-content-end align-items-center">
        <?php if (!isset($_SESSION['email']) || empty($_SESSION['email'])) { ?>
          <!-- VERIFICA SI ESTA LOGUEADO/ SI NO LO ESTA, MUESTRA LOS DOS BOTONES-->
          <div class="d-flex gap-1">
            <button type="button" class="btn btn-sm bnt-ingresar" data-bs-toggle="modal" data-bs-target="#modalLoguin">
              <span class="d-none d-sm-inline">Ingresar</span>
              <i class="bi bi-box-arrow-in-right d-sm-none"></i>
            </button>
            <button type="button" class="btn btn-sm bnt-registrar" data-bs-toggle="modal" data-bs-target="#modalRegistrar">
              <span class="d-none d-sm-inline">Registrate</span>
              <i class="bi bi-person-plus d-sm-none"></i>
            </button>
          </div>
        <?php } else { ?>
          <!--SI ESTA LOGUIEADO MUESTRA EL BOTON DE CERRAR SESION-->
          <a href="<?php echo BASE_URL; ?>/src/Controllers/cerrarSesion.php" class="btn btn-danger btn-sm">
            <i class="bi bi-box-arrow-right"></i>
            <span class="d-none d-md-inline ms-1">Salir</span>
          </a>
        <?php } ?>
      </div>
    </div>
  </div>

  <!-- Offcanvas mejorado -->
  <div
    class="offcanvas offcanvas-start text-bg-dark"
    tabindex="-1"
    id="offcanvasDarkNavbar"
    aria-labelledby="offcanvasDarkNavbarLabel">
    <div class="offcanvas-header bg-secondary text-white">
      <h5 class="offcanvas-title" id="offcanvasNavbarLabel">
        <i class="bi bi-person-circle me-2"></i>
        Bienvenido
      </h5>
      <button
        type="button"
        class="btn-close btn-close-white"
        data-bs-dismiss="offcanvas"
        aria-label="Close"></button>
    </div>
    
    <!-- Información del usuario -->
    <div class="p-3 border-bottom border-secondary">
      <div class="text-center">
        <i class="bi bi-person-badge fs-2 text-primary mb-2"></i>
        <p class="mb-1 text-light">
          <strong>
            <?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email'], ENT_QUOTES, 'UTF-8') : 'Inicie Sesión'; ?>
          </strong>
        </p>
        <p class="mb-0 text-muted small">
          <i class="bi bi-shield-check me-1"></i>
          <?php echo isset($_SESSION['nombre_rol']) ? htmlspecialchars($_SESSION['nombre_rol'], ENT_QUOTES, 'UTF-8') : 'Invitado'; ?>
        </p>
      </div>
    </div>

    <!--LISTADO DE TABLA LATERAL SEGUN ROL-->
    <div class="list-group-flush text-start p-3 m-0 border-0">
      <ul class="list-group list-group-flush text-white text-start">
        <li class="list-group-item bg-transparent border-0 text-white py-2">
          <a href="<?php echo BASE_URL; ?>/index.php" class="text-white text-decoration-none d-flex align-items-center">
            <i class="bi bi-house-door me-2"></i>
            <span>Inicio</span>
          </a>
        </li>
        <!--SE MUESTRA SEGUN ROL-->
        <?php require_once __DIR__ . '/../Controllers/navBarListGroup.php'; ?>
      </ul>
    </div>
  </div>
</nav>