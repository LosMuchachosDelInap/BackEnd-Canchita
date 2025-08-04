<?php
require_once __DIR__ . '/../Template/cors.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../Model/Contacto.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $data) {
    $email = trim($data['email'] ?? '');
    $mensaje = trim($data['mensaje'] ?? '');

    if (empty($email) || empty($mensaje)) {
        echo json_encode(['success' => false, 'message' => 'Debe completar todos los campos.']);
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

        $mail->setFrom($contacto->getEmail(), 'Consulta Web');
        $mail->addAddress($_ENV['MAIL_USERNAME']);

        $mail->isHTML(true);
        $mail->Subject = 'Nueva consulta desde el sitio';
        $mail->Body = "
            <b>Usuario:</b> " . htmlspecialchars($contacto->getEmail()) . "<br>
            <b>Mensaje:</b><br>" . nl2br(htmlspecialchars($contacto->getMensaje()));

        $mail->send();
        echo json_encode(['success' => true, 'message' => '¡Consulta enviada correctamente!']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'No se pudo enviar el mensaje. Error: ' . $mail->ErrorInfo]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Método no permitido o datos inválidos.']);
exit;
