<?php
// muestra los errores en el navegador ,soi los hay
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Definir BASE_URL solo si no está definida
if (!defined('BASE_URL')) {
    $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    //  $carpeta = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    // $carpeta = '/Mis_proyectos/IFTS12-LaCanchitaDeLosPibes';// XAMPP
    $carpeta = ''; // localhost:8000
    define('BASE_URL', $protocolo . $host . $carpeta);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../ConectionBD/CConection.php';
$conn = (new ConectionDB())->getConnection();

// carga el select de canchas
$canchas = [];
$result = $conn->query("SELECT id_cancha, nombreCancha, precio FROM cancha WHERE habilitado = 1 AND cancelado = 0");
while ($row = $result->fetch_assoc()) {
    $canchas[] = $row;
}

// Selección de cancha (puedes agregar un select para elegirla)
$id_cancha = $_GET['cancha'] ?? 1;

// Obtener reservas existentes para la cancha seleccionada
$reservas = [];
$sql = "SELECT r.id_reserva, r.id_cancha, f.fecha, h.horario
        FROM reserva r
        JOIN fecha f ON r.id_fecha = f.id_fecha
        JOIN horario h ON r.id_horario = h.id_horario
        WHERE r.id_cancha = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_cancha);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $reservas[] = [
        'title' => 'Reservado',
        'start' => $row['fecha'] . 'T' . $row['horario'],
        'color' => '#ccc'
    ];
}
?>
    <!--Chequea que alla usuario logueado, si lo esta,lo guarda en la variable-->
    <script>
        var usuarioLogueado = <?php echo isset($_SESSION['id_usuario']) ? 'true' : 'false'; ?>;
    </script>
    <!---------------------------------------------------------------------------------------------------->

<!DOCTYPE html>
<html lang="es">
<?php require_once __DIR__ . '/../Template/head.php'; ?>

<body class="content">
    <!--Chequea que alla usuario logueado, si lo esta,lo guarda en la variable-->
    <script>
        var usuarioLogueado = <?php echo isset($_SESSION['id_usuario']) ? 'true' : 'false'; ?>;
    </script>
    <!---------------------------------------------------------------------------------------------------->
    <?php require_once __DIR__ . '/../Template/navBar.php'; ?>
    <div class="centrar">
        <div class="mt-5 card col-10 bg-dark text-white border border-secondary">
            <h5 class="card-header border border-secondary">
                <?php if (isset($_SESSION['reserva_ok'])): ?>
                    <div class="alert alert-success" id="mensaje-flash">
                        <i class="bi bi-check-circle me-2"></i>
                        <?= htmlspecialchars($_SESSION['reserva_ok'], ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                    <?php unset($_SESSION['reserva_ok']); ?>
                <?php endif; ?>
                <?php if (isset($_SESSION['reserva_error'])): ?>
                    <div class="alert alert-danger" id="mensaje-flash-error">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <?= htmlspecialchars($_SESSION['reserva_error'], ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                    <?php unset($_SESSION['reserva_error']); ?>
                <?php endif; ?>
                <i class="bi bi-calendar-event me-2"></i>
                Reservar Cancha
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                    <div class="d-block d-sm-inline">
                        <span class="text-muted">|</span>
                        <span class="text-info">Usuario: <?= htmlspecialchars($_SESSION['email'] ?? 'No disponible', ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                <?php endif; ?>
            </h5>
            <div class="card-body bg-dark">
                <div class="text-center">
                    <div class="row g-3">
                        <!-- Sidebar con controles -->
                        <div class="col-12 col-lg-3">
                            <div class="bg-secondary p-3 rounded">
                                <h6 class="text-white mb-3">
                                    <i class="bi bi-gear me-2"></i>
                                    Configuración
                                </h6>
                                
                                <!-- Selector de cancha -->
                                <div class="mb-3">
                                    <label for="canchaSelect" class="form-label text-white">
                                        <i class="bi bi-building me-1"></i>
                                        Seleccionar Cancha
                                    </label>
                                    <select id="canchaSelect" class="form-select">
                                        <?php foreach ($canchas as $cancha): ?>
                                            <option value="<?= $cancha['id_cancha'] ?>" <?= ($cancha['id_cancha'] == $id_cancha) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($cancha['nombreCancha']) ?> - $<?= number_format($cancha['precio'], 0, ',', '.') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <!-- Botones de vista -->
                                <div class="mb-3">
                                    <label class="form-label text-white">
                                        <i class="bi bi-eye me-1"></i>
                                        Vista del Calendario
                                    </label>
                                    <div class="btn-group d-flex" role="group">
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="calendar.changeView('dayGridMonth')">
                                            <i class="bi bi-calendar-month"></i>
                                            <span class="d-none d-sm-inline ms-1">Mes</span>
                                        </button>
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="calendar.changeView('timeGridWeek')">
                                            <i class="bi bi-calendar-week"></i>
                                            <span class="d-none d-sm-inline ms-1">Semana</span>
                                        </button>
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="calendar.changeView('timeGridDay')">
                                            <i class="bi bi-calendar-day"></i>
                                            <span class="d-none d-sm-inline ms-1">Día</span>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Leyenda -->
                                <div class="mb-3">
                                    <label class="form-label text-white">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Leyenda
                                    </label>
                                    <div class="legend-item">
                                        <span class="legend-color" style="background-color: #28a745;"></span>
                                        <span class="text-white">Disponible</span>
                                    </div>
                                    <div class="legend-item">
                                        <span class="legend-color" style="background-color: #dc3545;"></span>
                                        <span class="text-white">Ocupado</span>
                                    </div>
                                    <div class="legend-item">
                                        <span class="legend-color" style="background-color: #ffc107;"></span>
                                        <span class="text-white">Pendiente</span>
                                    </div>
                                </div>
                                
                                <!-- Instrucciones -->
                                <div class="alert alert-info">
                                    <i class="bi bi-lightbulb me-2"></i>
                                    <strong>Instrucciones:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Haz clic en una fecha disponible</li>
                                        <li>Selecciona el horario deseado</li>
                                        <li>Confirma tu reserva</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Calendario -->
                        <div class="col-12 col-lg-9">
                            <div id="calendar" class="calendar-container"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var calendar; // Variable global para el calendario

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var reservas = <?php echo json_encode($reservas); ?>;

            var calendar = new FullCalendar.Calendar(calendarEl, {
                // Configuración de vistas disponibles
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },

                // Vista inicial
                initialView: 'timeGridWeek', // Puedes cambiar a: dayGridMonth, timeGridDay, listWeek

                // Configuración de horarios
                slotMinTime: "08:00:00", // Hora de inicio
                slotMaxTime: "22:00:00", // Hora de fin
                slotDuration: '01:00:00', // Duración de cada slot (1 hora)
                slotLabelInterval: '01:00', // Intervalos en las etiquetas

                // Configuración general
                allDaySlot: false, // Ocultar slot de "todo el día"
                locale: 'es', // Idioma español
                height: 'auto', // Altura automática
                expandRows: true, // Expandir filas

                // Configuración de días
                firstDay: 1, // Lunes como primer día (0=Domingo, 1=Lunes)
                weekends: true, // Mostrar fines de semana

                // Eventos y reservas
                events: reservas,

                // Configuración de selección
                selectable: true,
                selectOverlap: false,
                selectMirror: true, // Mostrar preview de selección

                // Configuración de tiempo
                nowIndicator: true, // Mostrar línea de tiempo actual

                // Configuración visual
                eventDisplay: 'block',

                // Textos personalizados
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    day: 'Día',
                    list: 'Lista'
                },

                // Configuración de vista de mes
                dayMaxEvents: 3, // Máximo 3 eventos por día en vista mensual
                moreLinkText: 'más', // Texto para "ver más eventos"

                select: function(info) {
                    if (!usuarioLogueado) {
                        // Mostrar modal de login
                        var modalLoguin = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalLoguin'));
                        modalLoguin.show();
                        calendar.unselect();
                        return;
                    }

                    var startDate = info.start;
                    var endDate = info.end;
                    var minutos = Math.abs((endDate - startDate) / (1000 * 60));

                    if (minutos !== 60) {
                        alert('Solo puedes reservar turnos de 1 hora.');
                        calendar.unselect();
                        return;
                    }

                    // Verificar que no sea una fecha pasada
                    var ahora = new Date();
                    if (startDate < ahora) {
                        alert('No puedes reservar en fechas pasadas.');
                        calendar.unselect();
                        return;
                    }

                    var fechaFormateada = startDate.toLocaleDateString('es-ES');
                    var horaFormateada = startDate.toLocaleTimeString('es-ES', {
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    // Obtener información de la cancha seleccionada
                    var canchaInfo = document.getElementById('canchaSelect');
                    var canchaTexto = canchaInfo.options[canchaInfo.selectedIndex].text;

                    if (confirm(`¿Confirmas la reserva?\n\nFecha: ${fechaFormateada}\nHora: ${horaFormateada}\nCancha: ${canchaTexto}`)) {
                        // Forzar formato HH:MM:SS
                        var dateObj = info.start;
                        var horas = String(dateObj.getHours()).padStart(2, '0');
                        var minutos = String(dateObj.getMinutes()).padStart(2, '0');
                        var segundos = '00';
                        var horario = horas + ':' + minutos + ':' + segundos;

                        $.post('<?php echo BASE_URL; ?>/src/Controllers/reservarCanchaController.php', {
                            action: 'reservar',
                            cancha: document.getElementById('canchaSelect').value,
                            fecha: info.startStr.split('T')[0],
                            horario: horario
                        }, function(response) {
                            if (response === 'ok') {
                                alert('¡Reserva realizada exitosamente!');
                                location.reload();
                            } else {
                                alert('Error: ' + response);
                            }
                        }).fail(function() {
                            alert('Error de conexión. Inténtalo de nuevo.');
                        });
                    }
                    calendar.unselect();
                },

                eventClick: function(info) {
                    alert('Este turno ya está reservado.');
                },

                // Personalizar el contenido de los eventos
                eventContent: function(arg) {
                    return {
                        html: '<div style="font-size: 12px; text-align: center; padding: 2px;">🚫 Reservado</div>'
                    };
                },

                // Callback cuando cambia la vista
                viewDidMount: function(info) {
                    updateViewButtons(info.view.type);
                }
            });
            
            calendar.render();
            
            // Manejar cambio de cancha
            document.getElementById('canchaSelect').addEventListener('change', function() {
                var nuevaCancha = this.value;
                window.location.href = '?cancha=' + nuevaCancha;
            });
        });

        // Función para cambiar la vista del calendario
        function changeCalendarView(viewName) {
            if (calendar) {
                calendar.changeView(viewName);
            }
        }

        // Función para actualizar los botones activos
        function updateViewButtons(currentView) {
            // Actualizar botones de vista
            console.log('Vista actual:', currentView);
        }

        // Mensaje flash que desaparece automáticamente
        document.addEventListener('DOMContentLoaded', function() {
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

            const mensajeFlashError = document.getElementById('mensaje-flash-error');
            if (mensajeFlashError) {
                setTimeout(() => {
                    mensajeFlashError.style.opacity = '0';
                    mensajeFlashError.style.transition = 'opacity 0.5s ease-out';
                    setTimeout(() => {
                        mensajeFlashError.remove();
                    }, 500);
                }, 3000);
            }
        });
    </script>
    
    <?php
    include_once(__DIR__ . '/../Template/footer.php');
    include_once(__DIR__ . "/../Components/modalLoguin.php");
    include_once(__DIR__ . "/../Components/modalRegistrar.php");
    include_once(__DIR__ . "/../Components/modalContactos.php");
    ?>
    
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
</body>
</html>