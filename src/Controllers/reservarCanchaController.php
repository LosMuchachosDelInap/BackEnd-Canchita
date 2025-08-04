<?php
require_once __DIR__ . '/../Template/cors.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../Model/Contacto.php';
require_once __DIR__ . '/mail_config.php';
require_once __DIR__ . '/../ConectionBD/CConection.php';
require_once __DIR__ . '/../Model/Reserva.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$conn = (new ConectionDB())->getConnection();

$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $data) {
    $id_usuario = $data['id_usuario'] ?? null;
    $id_cancha = isset($data['cancha']) ? intval($data['cancha']) : null;
    $fecha = $data['fecha'] ?? null;
    $horario = $data['horario'] ?? null;

    if (!$id_usuario || !$id_cancha || !$fecha || !$horario) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos para la reserva.']);
        exit;
    }

    // Obtener o crear id_fecha
    $stmt = $conn->prepare("SELECT id_fecha FROM fecha WHERE fecha = ?");
    $stmt->bind_param("s", $fecha);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $id_fecha = $row['id_fecha'];
    } else {
        $stmt_insert = $conn->prepare("INSERT INTO fecha (fecha) VALUES (?)");
        $stmt_insert->bind_param("s", $fecha);
        if ($stmt_insert->execute()) {
            $id_fecha = $conn->insert_id;
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al guardar la fecha']);
            exit;
        }
    }

    // Obtener id_horario
    $stmt = $conn->prepare("SELECT id_horario FROM horario WHERE horario = ?");
    $stmt->bind_param("s", $horario);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $id_horario = $row ? $row['id_horario'] : null;

    if ($id_fecha && $id_horario) {
        $reserva = new Reserva($id_usuario, $id_cancha, $id_fecha, $id_horario);
        if ($reserva->guardar($conn)) {
            // --- Envío de mail de confirmación con PHPMailer ---
            $stmt = $conn->prepare("SELECT u.email, p.nombre, p.apellido FROM usuario u 
                                   JOIN persona p ON u.id_persona = p.id_persona 
                                   WHERE u.id_usuario = ?");
            $stmt->bind_param("i", $id_usuario);
            $stmt->execute();
            $result = $stmt->get_result();
            $rowUser = $result->fetch_assoc();
            $emailUsuario = $rowUser ? $rowUser['email'] : '';
            $nombreCompleto = $rowUser ? $rowUser['nombre'] . ' ' . $rowUser['apellido'] : 'Usuario';

            $stmt = $conn->prepare("SELECT nombreCancha FROM cancha WHERE id_cancha = ?");
            $stmt->bind_param("i", $id_cancha);
            $stmt->execute();
            $result = $stmt->get_result();
            $rowCancha = $result->fetch_assoc();
            $nombreCancha = $rowCancha ? $rowCancha['nombreCancha'] : 'Cancha';

            if ($emailUsuario && filter_var($emailUsuario, FILTER_VALIDATE_EMAIL)) {
                $mail = new PHPMailer(true);
                try {
                    if (empty($_ENV['MAIL_HOST']) || empty($_ENV['MAIL_USERNAME']) || empty($_ENV['MAIL_PASSWORD'])) {
                        throw new Exception('Configuración de correo incompleta en .env');
                    }
                    $mail->isSMTP();
                    $mail->Host       = $_ENV['MAIL_HOST'];
                    $mail->SMTPAuth   = $_ENV['MAIL_SMTPAuth'] === 'true';
                    $mail->Username   = $_ENV['MAIL_USERNAME'];
                    $mail->Password   = $_ENV['MAIL_PASSWORD'];
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = intval($_ENV['MAIL_PORT']);
                    $mail->SMTPOptions = array(
                        'ssl' => array(
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true
                        )
                    );
                    $mail->setFrom($_ENV['MAIL_USERNAME'], 'La Canchita de los Pibes');
                    $mail->addAddress($emailUsuario, $nombreCompleto);

                    $mail->isHTML(true);
                    $mail->Subject = 'Confirmación de Reserva - La Canchita de los Pibes';
                    $mail->Body = "
                        <h3>¡Hola $nombreCompleto!</h3>
                        <p>Tu reserva fue realizada con éxito.</p>
                        <div style='background-color: #f8f9fa; padding: 15px; border-left: 4px solid #28a745; margin: 15px 0;'>
                            <h4>Detalles de tu reserva:</h4>
                            <ul>
                                <li><strong>Cancha:</strong> $nombreCancha</li>
                                <li><strong>Fecha:</strong> " . date('d/m/Y', strtotime($fecha)) . "</li>
                                <li><strong>Horario:</strong> " . date('H:i', strtotime($horario)) . " hs</li>
                            </ul>
                        </div>
                        <p>¡Te esperamos!<br><strong>La Canchita de los Pibes</strong></p>
                        <hr>
                        <small><em>Si tienes alguna consulta, no dudes en contactarnos.</em></small>
                    ";
                    $mail->AltBody = "¡Hola $nombreCompleto!\n\n"
                        . "Tu reserva fue realizada con éxito.\n\n"
                        . "Detalles de tu reserva:\n"
                        . "- Cancha: $nombreCancha\n"
                        . "- Fecha: " . date('d/m/Y', strtotime($fecha)) . "\n"
                        . "- Horario: " . date('H:i', strtotime($horario)) . " hs\n\n"
                        . "¡Te esperamos!\nLa Canchita de los Pibes";
                    $mail->send();
                } catch (Exception $e) {
                    // No detener el proceso si falla el envío del correo
                }
            }
            echo json_encode(['success' => true, 'message' => 'Reserva realizada con éxito.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al guardar la reserva.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Fecha u horario inválido.']);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Método no permitido o datos inválidos.']);
exit;
