<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Inicia la sesión antes de cualquier salida
if (session_status() === PHP_SESSION_NONE) {
      session_start();
}
// Llamo al archivo de la clase de conexión (lo requiero para poder instanciar la clase)
require_once __DIR__ . '/src/ConectionBD/CConection.php';
// Instanciao la clase
$conectarDB = new ConectionDB();
// Obtengo la conexión
$conn = $conectarDB->getConnection();
?>
<!DOCTYPE html>
<html lang="es">

<?php include_once(__DIR__ . "/src/Template/head.php"); ?>

<body class="content">
      <?php include_once(__DIR__ . "/src/Template/navBar.php"); ?>

      <div class="centrar">
            <div class="mt-5 card col-10 bg-dark text-white border border-secondary">
                  <h5 class="card-header border border-secondary">
                        <i class="bi bi-trophy text-warning me-2"></i>
                        Bienvenido a La Canchita de los Pibes
                        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                              <div class="d-block d-sm-inline">
                                    <span class="text-muted">|</span>
                                    <span class="text-info">Usuario: <?= htmlspecialchars($_SESSION['email'] ?? 'No disponible', ENT_QUOTES, 'UTF-8'); ?></span>
                              </div>
                        <?php endif; ?>
                  </h5>
                  <div class="card-body bg-dark">
                        <div class="text-center">
                              <div class="row g-4">
                                    <!-- Información principal -->
                                    <div class="col-12 col-lg-6">
                                          <h3 class="text-success mb-4">
                                                <i class="bi bi-calendar-event me-2"></i>
                                                Sistema de Reservas
                                          </h3>
                                          <p class="lead mb-4">
                                                Reserva tu cancha favorita de forma rápida y sencilla. 
                                                Gestiona tus horarios y disfruta del mejor fútbol.
                                          </p>
                                          
                                          <div class="row text-center mt-4">
                                                <div class="col-6 col-md-6 mb-3">
                                                      <div class="bg-success bg-opacity-25 p-3 rounded h-100">
                                                            <i class="bi bi-calendar-check fs-1 text-success mb-2"></i>
                                                            <h6 class="mt-2 mb-2">Reservas Online</h6>
                                                            <p class="small mb-0">Sistema 24/7 disponible</p>
                                                      </div>
                                                </div>
                                                <div class="col-6 col-md-6 mb-3">
                                                      <div class="bg-primary bg-opacity-25 p-3 rounded h-100">
                                                            <i class="bi bi-people fs-1 text-primary mb-2"></i>
                                                            <h6 class="mt-2 mb-2">Fácil Gestión</h6>
                                                            <p class="small mb-0">Administra tus reservas</p>
                                                      </div>
                                                </div>
                                          </div>
                                    </div>
                                    
                                    <!-- Panel de acciones -->
                                    <div class="col-12 col-lg-6">
                                          <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                                                <!-- Usuario logueado -->
                                                <h4 class="text-info mb-4">
                                                      <i class="bi bi-person-check me-2"></i>
                                                      Panel de Usuario
                                                </h4>
                                                <div class="d-grid gap-3">
                                                      <a href="src/Views/reservarCancha.php" class="btn btn-success btn-lg">
                                                            <i class="bi bi-calendar-plus me-2"></i>
                                                            <span>Reservar Cancha</span>
                                                      </a>
                                                      
                                                      <?php if (in_array($_SESSION['nombre_rol'] ?? '', ['Administrador', 'Dueño'])): ?>
                                                            <a href="src/Views/listado.php" class="btn btn-primary">
                                                                  <i class="bi bi-people me-2"></i>
                                                                  <span>Gestionar Empleados</span>
                                                            </a>
                                                            <a href="src/Views/listadoReservas.php" class="btn btn-info">
                                                                  <i class="bi bi-list-check me-2"></i>
                                                                  <span>Ver Reservas</span>
                                                            </a>
                                                      <?php endif; ?>
                                                      
                                                      <a href="src/Controllers/cerrarSesion.php" class="btn btn-outline-danger">
                                                            <i class="bi bi-box-arrow-right me-2"></i>
                                                            <span>Cerrar Sesión</span>
                                                      </a>
                                                </div>
                                                
                                                <div class="alert alert-info mt-4">
                                                      <i class="bi bi-info-circle me-2"></i>
                                                      <strong>¡Bienvenido!</strong> Tu sesión está activa.
                                                </div>
                                          <?php else: ?>
                                                <!-- Usuario no logueado -->
                                                <h4 class="text-warning mb-4">
                                                      <i class="bi bi-lock me-2"></i>
                                                      Acceso al Sistema
                                                </h4>
                                                <p class="mb-4">Para reservar una cancha, necesitas iniciar sesión o registrarte.</p>
                                                
                                                <div class="d-grid gap-3">
                                                      <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#modalLoguin">
                                                            <i class="bi bi-box-arrow-in-right me-2"></i>
                                                            <span>Iniciar Sesión</span>
                                                      </button>
                                                      
                                                      <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalRegistrar">
                                                            <i class="bi bi-person-plus me-2"></i>
                                                            <span>Registrarse</span>
                                                      </button>
                                                      
                                                      <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#modalContactos">
                                                            <i class="bi bi-envelope me-2"></i>
                                                            <span>Contactanos</span>
                                                      </button>
                                                </div>
                                                
                                                <div class="alert alert-warning mt-4">
                                                      <i class="bi bi-exclamation-triangle me-2"></i>
                                                      <strong>¡Regístrate!</strong> Es rápido y gratuito.
                                                </div>
                                          <?php endif; ?>
                                    </div>
                              </div>
                              
                              <!-- Información adicional -->
                              <hr class="my-4">
                              <div class="row">
                                    <div class="col-12 col-sm-4">
                                          <div class="text-center p-3">
                                                <i class="bi bi-clock fs-2 text-success"></i>
                                                <h6 class="mt-2">Horarios</h6>
                                                <p class="small text-muted">Lunes a Domingo<br>8:00 - 22:00</p>
                                          </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                          <div class="text-center p-3">
                                                <i class="bi bi-geo-alt fs-2 text-primary"></i>
                                                <h6 class="mt-2">Ubicación</h6>
                                                <p class="small text-muted">Buenos Aires<br>Argentina</p>
                                          </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                          <div class="text-center p-3">
                                                <i class="bi bi-phone fs-2 text-info"></i>
                                                <h6 class="mt-2">Contacto</h6>
                                                <p class="small text-muted">+54 11 1234-5678<br>info@canchitapibes.com</p>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
      <div class="footer">
            <?php include_once(__DIR__ . "/src/Template/footer.php"); ?>
      </div>
      
      <?php
      include_once(__DIR__ . "/src/Components/modalLoguin.php");
      include_once(__DIR__ . "/src/Components/modalRegistrar.php");
      include_once(__DIR__ . "/src/Components/modalContactos.php");
      ?>
      <!--al hacer click en registrar muestra el mensaje y espera 3 seg,luego se cierra y muyestra el loguin-->
      <script>
            document.addEventListener("DOMContentLoaded", function() {
                  // Verifica si hay mensaje de registro exitoso
                  <?php if (isset($_SESSION['registro_message'])): ?>
                        // Espera 3 segundos, cierra el modal de registro y abre el de login
                        setTimeout(function() {
                              var modalRegistrar = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalRegistrar'));
                              modalRegistrar.hide();
                              var modalLoguin = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalLoguin'));
                              modalLoguin.show();
                        }, 3000);
                  <?php
                        unset($_SESSION['registro_message']); // Limpia el mensaje para no mostrarlo de nuevo
                  endif; ?>
            });
      </script>
      <!-- Si hay un mensaje de error, muestra el modal de login-->
      <?php if (isset($_SESSION['error_message'])): ?>

            <script>
                  document.addEventListener('DOMContentLoaded', function() {
                        var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalLoguin'));
                        modal.show();
                  });
            </script>
             <?php unset($_SESSION['error_message']); ?>
      <?php endif; ?>
      <script>
// JavaScript para mejorar la experiencia responsive
document.addEventListener('DOMContentLoaded', function() {
    
    // Función para mensajes flash que desaparecen automáticamente
    const mensajeFlash = document.getElementById('mensaje-flash');
    if (mensajeFlash) {
        setTimeout(() => {
            mensajeFlash.style.opacity = '0';
            mensajeFlash.style.transition = 'opacity 0.5s ease-out';
            setTimeout(() => {
                mensajeFlash.remove();
            }, 500);
        }, 3000);
    }
    
    // Validación del formulario de empleados
    const formCrearEmpleado = document.getElementById('formCrearEmpleado');
    if (formCrearEmpleado) {
        const inputs = formCrearEmpleado.querySelectorAll('input[required], select[required]');
        const btnCrear = document.getElementById('crearEmpleadoBtn');
        
        function validarFormulario() {
            let todosCompletos = true;
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    todosCompletos = false;
                }
            });
            
            btnCrear.disabled = !todosCompletos;
            btnCrear.classList.toggle('btn-primary', todosCompletos);
            btnCrear.classList.toggle('btn-secondary', !todosCompletos);
        }
        
        inputs.forEach(input => {
            input.addEventListener('input', validarFormulario);
            input.addEventListener('change', validarFormulario);
        });
        
        // Validación específica para DNI
        const dniInput = document.getElementById('dni');
        if (dniInput) {
            dniInput.addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
            });
        }
        
        // Validación específica para teléfono
        const telefonoInput = document.getElementById('telefono');
        if (telefonoInput) {
            telefonoInput.addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/[^0-9\-\+\(\)\ ]/g, '');
            });
        }
        
        // Validación específica para edad
        const edadInput = document.getElementById('edad');
        if (edadInput) {
            edadInput.addEventListener('input', function(e) {
                const valor = parseInt(e.target.value);
                if (valor < 18) e.target.setCustomValidity('La edad mínima es 18 años');
                else if (valor > 65) e.target.setCustomValidity('La edad máxima es 65 años');
                else e.target.setCustomValidity('');
            });
        }
    }
    
    // Mejorar responsive del navbar
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        let lastScrollTop = 0;
        window.addEventListener('scroll', function() {
            let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            if (scrollTop > lastScrollTop && scrollTop > 100) {
                navbar.style.transform = 'translateY(-100%)';
            } else {
                navbar.style.transform = 'translateY(0)';
            }
            lastScrollTop = scrollTop;
        });
    }
    
    // Cerrar offcanvas al hacer clic en un enlace (mejor UX en móviles)
    const offcanvasLinks = document.querySelectorAll('.offcanvas a');
    offcanvasLinks.forEach(link => {
        link.addEventListener('click', function() {
            const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('offcanvasDarkNavbar'));
            if (offcanvas) {
                offcanvas.hide();
            }
        });
    });
    
    // Mejorar tablas responsivas
    const tablas = document.querySelectorAll('.table');
    tablas.forEach(tabla => {
        if (!tabla.closest('.table-responsive')) {
            const wrapper = document.createElement('div');
            wrapper.classList.add('table-responsive');
            tabla.parentNode.insertBefore(wrapper, tabla);
            wrapper.appendChild(tabla);
        }
    });
    
    // Tooltip para botones pequeños en móviles
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Mejorar accesibilidad del teclado
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modales = document.querySelectorAll('.modal.show');
            modales.forEach(modal => {
                const modalInstance = bootstrap.Modal.getInstance(modal);
                if (modalInstance) modalInstance.hide();
            });
            
            const offcanvas = document.querySelector('.offcanvas.show');
            if (offcanvas) {
                const offcanvasInstance = bootstrap.Offcanvas.getInstance(offcanvas);
                if (offcanvasInstance) offcanvasInstance.hide();
            }
        }
    });
    
    // Optimizar rendimiento en móviles
    if (window.matchMedia('(max-width: 768px)').matches) {
        // Reducir animaciones en móviles
        document.body.style.setProperty('--animation-duration', '0.2s');
        
        // Optimizar imágenes
        const imagenes = document.querySelectorAll('img');
        imagenes.forEach(img => {
            img.loading = 'lazy';
        });
    }
    
    // Detectar orientación y ajustar layout
    window.addEventListener('orientationchange', function() {
        setTimeout(() => {
            // Reajustar elementos después del cambio de orientación
            const calendar = document.getElementById('calendar');
            if (calendar && window.calendar) {
                window.calendar.updateSize();
            }
        }, 100);
    });
    
    // Mejorar performance del scroll
    let ticking = false;
    function updateScrollPosition() {
        // Actualizaciones relacionadas con el scroll
        ticking = false;
    }
    
    function requestTick() {
        if (!ticking) {
            requestAnimationFrame(updateScrollPosition);
            ticking = true;
        }
    }
    
    window.addEventListener('scroll', requestTick);
    
    console.log('Sistema responsive cargado correctamente');
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>