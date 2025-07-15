<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!defined('BASE_URL')) {
    $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $carpeta = '';
    define('BASE_URL', $protocolo . $host . $carpeta);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica si está logueado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ' . BASE_URL . '/Views/noInicioSesion.php');
    exit;
}

// Verifica si el rol NO es Administrador ni Dueño
$rol = $_SESSION['nombre_rol'] ?? '';
if ($rol !== 'Administrador' && $rol !== 'Dueño') {
    header('Location: ' . BASE_URL . '/Views/noAutorizado.php');
    exit;
}

// Incluir archivos necesarios
require_once __DIR__ . '/../ConectionBD/CConection.php';
require_once __DIR__ . '/../Model/peticionesSql.php';

// Inicializar variables
$mensajeError = '';
$roles = [];
$empleados = [];

// Obtener conexión a la base de datos
try {
    $conectarDB = new ConectionDB();
    $conn = $conectarDB->getConnection();
    
    // Obtener roles
    $resultRoles = $conn->query("SELECT id_roles, rol FROM roles WHERE habilitado = 1");
    if ($resultRoles) {
        while ($row = $resultRoles->fetch_assoc()) {
            $roles[] = $row;
        }
    }
    
    // Obtener empleados (consulta corregida)
    $sqlEmpleados = "SELECT 
        e.id_empleado, 
        u.email, 
        p.nombre, 
        p.apellido, 
        p.edad, 
        p.dni, 
        p.telefono, 
        r.rol 
    FROM empleado e 
    JOIN usuario u ON e.id_usuario = u.id_usuario 
    JOIN persona p ON e.id_persona = p.id_persona 
    JOIN roles r ON e.id_rol = r.id_roles 
    WHERE e.habilitado = 1";

    $resultEmpleados = $conn->query($sqlEmpleados);
    if ($resultEmpleados) {
        while ($row = $resultEmpleados->fetch_assoc()) {
            $empleados[] = $row;
        }
    }
    
} catch (Exception $e) {
    $mensajeError = "Error al conectar con la base de datos: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<?php include_once __DIR__ . '/../Template/head.php'; ?>

<body class="content">
    <?php include_once __DIR__ . '/../Template/navBar.php'; ?>
    <div class="centrar">
        <div class="mt-5 card col-10 bg-dark text-white border border-secondary">
            <h5 class="card-header border border-secondary">
                <?php if (isset($_SESSION['mensaje'])): ?>
                    <div class="alert alert-success" id="mensaje-flash">
                        <?= htmlspecialchars($_SESSION['mensaje'], ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                    <?php unset($_SESSION['mensaje']); ?>
                <?php endif; ?>
                <i class="bi bi-people-fill me-2"></i>
                Gestión de Empleados
                <div class="d-block d-sm-inline">
                    <span class="text-muted">|</span>
                    <span class="text-info">Usuario: <?= htmlspecialchars($_SESSION['email'] ?? 'No disponible', ENT_QUOTES, 'UTF-8'); ?></span>
                    <span class="text-muted">|</span>
                    <span class="text-warning">Rol: <?= htmlspecialchars($_SESSION['nombre_rol'] ?? 'No disponible', ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
            </h5>
            <div class="card-body bg-dark">
                <div class="text-center">
                    <div class="row g-3">
                        <!-- Formulario crear empleado -->
                        <div class="col-12 col-lg-4 col-xl-3">
                            <h5 class="alert alert-secondary text-bg-dark mb-3">
                                <i class="bi bi-person-plus me-2"></i>
                                Nuevo Empleado
                            </h5>
                            <form method="post" action="listadoEmpleadosController.php" class="d-grid bg-dark p-3 rounded border border-secondary" id="formCrearEmpleado">
                                <?php if (!empty($mensajeError)): ?>
                                    <div class="alert alert-danger py-2"><?= htmlspecialchars($mensajeError) ?></div>
                                <?php endif; ?>
                                <div class="mb-2">
                                    <input type="email" name="email" id="email" placeholder="Email" class="form-control" required>
                                </div>
                                <div class="mb-2">
                                    <input type="password" name="clave" id="clave" placeholder="Contraseña" class="form-control" required>
                                </div>
                                <div class="mb-2">
                                    <input type="text" name="nombre" id="nombre" placeholder="Nombre" class="form-control" required>
                                </div>
                                <div class="mb-2">
                                    <input type="text" name="apellido" id="apellido" placeholder="Apellido" class="form-control" required>
                                </div>
                                <div class="mb-2">
                                    <input type="number" name="edad" id="edad" placeholder="Edad" class="form-control" min="18" max="65" required>
                                </div>
                                <div class="mb-2">
                                    <input type="text" name="dni" id="dni" placeholder="DNI" class="form-control" pattern="[0-9]{7,8}" required>
                                </div>
                                <div class="mb-2">
                                    <input type="tel" name="telefono" id="telefono" placeholder="Teléfono" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <select name="rol" id="rol" class="form-select" required>
                                        <option value="">Seleccionar rol</option>
                                        <?php foreach ($roles as $rol): ?>
                                            <option value="<?= $rol["id_roles"] ?>"><?= htmlspecialchars($rol["rol"]) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <button type="submit" name="crearEmpleado" id="crearEmpleadoBtn" class="btn btn-primary" disabled>
                                    <i class="bi bi-person-plus me-2"></i>
                                    <span>Crear Empleado</span>
                                </button>
                            </form>
                        </div>
                        
                        <!-- Tabla de empleados -->
                        <div class="col-12 col-lg-8 col-xl-9">
                            <div class="table-responsive">
                                <table class="table table-dark table-striped table-hover text-center">
                                    <thead class="table-primary">
                                        <tr>
                                            <th><i class="bi bi-hash"></i> ID</th>
                                            <th><i class="bi bi-envelope"></i> Email</th>
                                            <th><i class="bi bi-person"></i> Nombre</th>
                                            <th><i class="bi bi-person"></i> Apellido</th>
                                            <th><i class="bi bi-calendar3"></i> Edad</th>
                                            <th><i class="bi bi-card-text"></i> DNI</th>
                                            <th><i class="bi bi-telephone"></i> Teléfono</th>
                                            <th><i class="bi bi-shield-check"></i> Rol</th>
                                            <th><i class="bi bi-gear"></i> Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($empleados)): ?>
                                            <?php foreach ($empleados as $row): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row["id_empleado"]); ?></td>
                                                    <td><?= htmlspecialchars($row["email"]); ?></td>
                                                    <td><?= htmlspecialchars($row["nombre"]); ?></td>
                                                    <td><?= htmlspecialchars($row["apellido"]); ?></td>
                                                    <td><?= htmlspecialchars($row["edad"]); ?></td>
                                                    <td><?= htmlspecialchars($row["dni"]); ?></td>
                                                    <td><?= htmlspecialchars($row["telefono"]); ?></td>
                                                    <td><?= htmlspecialchars($row["rol"]); ?></td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <button class="btn btn-primary btn-sm" onclick="abrirModalModificar(<?= $row['id_empleado']; ?>)" title="Modificar empleado">
                                                                <i class="bi bi-pencil-square"></i>
                                                            </button>
                                                            <a class="btn btn-danger btn-sm" href="<?= BASE_URL; ?>/src/Controllers/eliminarEmpleado.php?id_empleado=<?= $row["id_empleado"]; ?>" 
                                                               onclick="return confirm('¿Está seguro de eliminar este empleado?')" title="Eliminar empleado">
                                                                <i class="bi bi-trash3-fill"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="9" class="text-muted">
                                                    <i class="bi bi-info-circle me-2"></i>
                                                    No hay empleados registrados.
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include_once __DIR__ . '/../Template/footer.php'; ?>
    <?php include_once __DIR__ . '/../Components/modalContactos.php'; ?>
    <?php if (file_exists(__DIR__ . '/../Components/modalModificarEmpleado.php')): ?>
        <?php include_once(__DIR__ . '/../Components/modalModificarEmpleado.php'); ?>
    <?php endif; ?>
    
    <script>
        // Constantes
        const BASE_URL = "<?= BASE_URL ?>";
        
        // Validación en tiempo real del formulario
        document.addEventListener('DOMContentLoaded', function() {
            const formCrearEmpleado = document.getElementById('formCrearEmpleado');
            if (formCrearEmpleado) {
                const campos = ['email', 'clave', 'nombre', 'apellido', 'edad', 'dni', 'telefono', 'rol'];
                const btnCrear = document.getElementById('crearEmpleadoBtn');
                
                // Función para validar todos los campos
                function validarCampos() {
                    let todosCompletos = true;
                    
                    campos.forEach(id => {
                        const elemento = document.getElementById(id);
                        if (elemento) {
                            const valor = elemento.value.trim();
                            if (!valor) {
                                todosCompletos = false;
                            }
                            
                            // Validaciones específicas
                            if (id === 'email' && valor) {
                                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                                if (!emailRegex.test(valor)) {
                                    todosCompletos = false;
                                }
                            }
                            
                            if (id === 'dni' && valor) {
                                if (valor.length < 7 || valor.length > 8) {
                                    todosCompletos = false;
                                }
                            }
                            
                            if (id === 'edad' && valor) {
                                const edad = parseInt(valor);
                                if (edad < 18 || edad > 65) {
                                    todosCompletos = false;
                                }
                            }
                        }
                    });
                    
                    // Actualizar estado del botón
                    if (btnCrear) {
                        btnCrear.disabled = !todosCompletos;
                        btnCrear.classList.toggle('btn-primary', todosCompletos);
                        btnCrear.classList.toggle('btn-secondary', !todosCompletos);
                    }
                }
                
                // Agregar listeners a todos los campos
                campos.forEach(id => {
                    const elemento = document.getElementById(id);
                    if (elemento) {
                        elemento.addEventListener('input', validarCampos);
                        elemento.addEventListener('change', validarCampos);
                        elemento.addEventListener('blur', validarCampos);
                    }
                });
                
                // Validación inicial
                validarCampos();
                
                // Formatear DNI solo números
                const dniInput = document.getElementById('dni');
                if (dniInput) {
                    dniInput.addEventListener('input', function(e) {
                        e.target.value = e.target.value.replace(/[^0-9]/g, '');
                    });
                }
                
                // Formatear teléfono
                const telefonoInput = document.getElementById('telefono');
                if (telefonoInput) {
                    telefonoInput.addEventListener('input', function(e) {
                        e.target.value = e.target.value.replace(/[^0-9\-\+\(\)\ ]/g, '');
                    });
                }
            }
            
            // Mensaje flash que desaparece automáticamente
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
        });
        
        // Función para abrir modal de modificación
        function abrirModalModificar(id_empleado) {
            const modalBody = document.getElementById('modalModificarEmpleadoBody');
            
            if (!modalBody) {
                alert('Error: No se encontró el modal de modificación');
                return;
            }
            
            // Mostrar mensaje de carga
            modalBody.innerHTML = '<div class="text-center p-3"><i class="bi bi-hourglass-split me-2"></i>Cargando...</div>';
            
            // Abrir modal
            const modal = new bootstrap.Modal(document.getElementById('modalModificarEmpleado'));
            modal.show();
            
            // Cargar contenido del modal
            fetch(BASE_URL + '/src/Views/modificar.php?id_empleado=' + id_empleado)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.text();
                })
                .then(html => {
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = html;
                    const form = tempDiv.querySelector('form');
                    
                    if (form) {
                        modalBody.innerHTML = '';
                        modalBody.appendChild(form);
                    } else {
                        modalBody.innerHTML = '<div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-2"></i>No se pudo cargar el formulario.</div>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    modalBody.innerHTML = '<div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-2"></i>Error al cargar el formulario: ' + error.message + '</div>';
                });
        }
        
        // Función para confirmar eliminación
        function confirmarEliminacion(id_empleado, nombre) {
            if (confirm(`¿Está seguro de eliminar al empleado "${nombre}"? Esta acción no se puede deshacer.`)) {
                window.location.href = BASE_URL + '/src/Controllers/eliminarEmpleado.php?id_empleado=' + id_empleado;
            }
        }
    </script>
</body>
</html>