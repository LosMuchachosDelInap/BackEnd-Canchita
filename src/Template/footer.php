<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Mensajes flash que desaparecen automáticamente
    const mensajes = document.querySelectorAll('#mensaje-flash, #mensaje-flash-error');
    mensajes.forEach(mensaje => {
      if (mensaje) {
        setTimeout(() => {
          mensaje.style.opacity = '0';
          mensaje.style.transition = 'opacity 0.5s ease-out';
          setTimeout(() => {
            mensaje.remove();
          }, 500);
        }, 4000);
      }
    });
    
    // Mejorar accesibilidad
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        const modales = document.querySelectorAll('.modal.show');
        modales.forEach(modal => {
          const modalInstance = bootstrap.Modal.getInstance(modal);
          if (modalInstance) modalInstance.hide();
        });
      }
    });
  });
</script>

<footer class="footer mt-auto py-3">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-12 col-md-6">
                <p class="footer-texto text-center text-md-start mb-2 mb-md-0">
                    <i class="bi bi-code-slash me-2"></i>
                    Desarrollado por Los Muchachos del Inap
                </p>
            </div>
            <div class="col-12 col-md-6">
                <div class="iconos-redes-sociales d-flex justify-content-center justify-content-md-end">
                    <a href="https://github.com/LosMuchachosDelInap/IFTS12-LaCanchitaDeLosPibes" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="me-2"
                       data-bs-toggle="tooltip"
                       data-bs-placement="top"
                       title="GitHub">
                        <i class="bi bi-github"></i>
                    </a>
                    <a href="https://instagram.com/" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="me-2"
                       data-bs-toggle="tooltip"
                       data-bs-placement="top"
                       title="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="https://linkedin.com/" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="me-2"
                       data-bs-toggle="tooltip"
                       data-bs-placement="top"
                       title="LinkedIn">
                        <i class="bi bi-linkedin"></i>
                    </a>
                    <a class="email" 
                       type="button" 
                       data-bs-toggle="modal" 
                       data-bs-target="#modalContactos"
                       data-bs-toggle="tooltip"
                       data-bs-placement="top"
                       title="Contacto">
                        <i class="bi bi-envelope"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>