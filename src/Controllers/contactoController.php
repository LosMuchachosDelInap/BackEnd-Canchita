<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../Model/Contacto.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $data) {
    $nombre = trim($data['nombre'] ?? '');
    $email = trim($data['email'] ?? '');
    $telefono = trim($data['telefono'] ?? '');
    $asunto = trim($data['asunto'] ?? '');
    $mensaje = trim($data['mensaje'] ?? '');

    if (empty($nombre) || empty($email) || empty($asunto) || empty($mensaje)) {
        echo json_encode(['success' => false, 'message' => 'Debe completar todos los campos obligatorios.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'El formato del email no es válido.']);
        exit;
    }

    $contacto = new Contacto($email, $mensaje);

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth   = $_ENV['MAIL_SMTPAuth'] === 'true';
        $mail->Username   = $_ENV['MAIL_USERNAME'];
        $mail->Password   = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $_ENV['MAIL_PORT'];

        $mail->setFrom($contacto->getEmail(), $nombre);
        $mail->addAddress($_ENV['MAIL_USERNAME']);

        $mail->isHTML(true);
        $mail->Subject = 'Contacto: ' . $asunto;
        $mail->Body = "
            <h2>Nueva consulta desde el sitio web</h2>
            <p><b>Nombre:</b> " . htmlspecialchars($nombre) . "</p>
            <p><b>Email:</b> " . htmlspecialchars($contacto->getEmail()) . "</p>
            <p><b>Teléfono:</b> " . htmlspecialchars($telefono) . "</p>
            <p><b>Asunto:</b> " . htmlspecialchars($asunto) . "</p>
            <p><b>Mensaje:</b></p>
            <p>" . nl2br(htmlspecialchars($contacto->getMensaje())) . "</p>
            <hr>
            <p><small>Enviado desde La Canchita de los Pibes - Sistema de Contacto</small></p>";

        $mail->send();
        echo json_encode(['success' => true, 'message' => '¡Consulta enviada correctamente! Te responderemos pronto.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'No se pudo enviar el mensaje. Error: ' . $mail->ErrorInfo]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Método no permitido o datos inválidos.']);
exit;
