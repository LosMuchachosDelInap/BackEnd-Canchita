<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/mail_config.php';

use Dotenv\Dotenv;

class EmailService {
    private $mail;
    
    public function __construct() {
        // Cargar variables de entorno
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();
        
        // Configurar PHPMailer
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->Host       = $_ENV['MAIL_HOST'];
        $this->mail->SMTPAuth   = $_ENV['MAIL_SMTPAuth'] === 'true';
        $this->mail->Username   = $_ENV['MAIL_USERNAME'];
        $this->mail->Password   = $_ENV['MAIL_PASSWORD'];
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port       = $_ENV['MAIL_PORT'];
        $this->mail->CharSet    = 'UTF-8';
        
        // Configurar remitente
        $this->mail->setFrom($_ENV['MAIL_USERNAME'], 'La Canchita de los Pibes');
    }
    
    /**
     * Envía email de confirmación de registro
     */
    public function enviarConfirmacionRegistro($email, $nombre, $apellido) {
        try {
            logMail("Iniciando envío de confirmación de registro para: $email");
            
            // Limpiar destinatarios previos
            $this->mail->clearAddresses();
            $this->mail->addAddress($email, "$nombre $apellido");
            
            $this->mail->isHTML(true);
            $this->mail->Subject = '¡Bienvenido/a a La Canchita de los Pibes!';
            
            $this->mail->Body = $this->generarHtmlConfirmacionRegistro($nombre, $apellido, $email);
            $this->mail->AltBody = $this->generarTextoConfirmacionRegistro($nombre, $apellido, $email);
            
            $resultado = $this->mail->send();
            
            if ($resultado) {
                logMail("✅ Email de confirmación enviado exitosamente a: $email");
                return [
                    'success' => true, 
                    'message' => 'Email de confirmación enviado correctamente'
                ];
            } else {
                logMail("❌ Error al enviar email de confirmación a: $email - " . $this->mail->ErrorInfo);
                return [
                    'success' => false, 
                    'message' => 'Error al enviar email de confirmación'
                ];
            }
            
        } catch (Exception $e) {
            logMail("❌ Excepción al enviar email de confirmación a: $email - " . $e->getMessage());
            return [
                'success' => false, 
                'message' => 'Error al enviar email: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Genera HTML para email de confirmación de registro
     */
    private function generarHtmlConfirmacionRegistro($nombre, $apellido, $email) {
        $nombreCompleto = htmlspecialchars("$nombre $apellido");
        $emailEscape = htmlspecialchars($email);
        
        return "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Bienvenido/a a La Canchita de los Pibes</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 0; background-color: #f4f4f4; }
                .container { max-width: 600px; margin: 20px auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
                .header { background: linear-gradient(135deg, #764ba2 0%, #667eea 100%); color: white; padding: 30px 20px; text-align: center; }
                .header h1 { margin: 0; font-size: 28px; }
                .content { padding: 30px 20px; }
                .welcome-box { background: #e8f5e8; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #4caf50; }
                .info-box { background: #f0f8ff; padding: 15px; border-radius: 6px; margin: 15px 0; }
                .button { display: inline-block; background: #4caf50; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; margin: 20px 0; font-weight: bold; }
                .footer { background: #333; color: #ccc; padding: 20px; text-align: center; font-size: 12px; }
                .social-links { margin: 10px 0; }
                .social-links a { color: #667eea; text-decoration: none; margin: 0 10px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>🏟️ La Canchita de los Pibes</h1>
                    <p>¡Te damos la bienvenida!</p>
                </div>
                
                <div class='content'>
                    <div class='welcome-box'>
                        <h2>¡Hola $nombreCompleto! 👋</h2>
                        <p>¡Tu registro fue exitoso! Ya eres parte de nuestra comunidad futbolera.</p>
                    </div>
                    
                    <h3>¿Qué puedes hacer ahora?</h3>
                    <ul>
                        <li>✅ <strong>Reservar canchas:</strong> Elige entre nuestras diferentes canchas disponibles</li>
                        <li>✅ <strong>Ver horarios:</strong> Consulta disponibilidad en tiempo real</li>
                        <li>✅ <strong>Gestionar reservas:</strong> Modifica o cancela tus reservas</li>
                        <li>✅ <strong>Contactanos:</strong> Cualquier duda o sugerencia</li>
                    </ul>
                    
                    <div class='info-box'>
                        <p><strong>📧 Tu cuenta:</strong> $emailEscape</p>
                        <p><strong>🔐 Tip de seguridad:</strong> Mantén tu contraseña segura y no la compartas.</p>
                    </div>
                    
                    <center>
                        <a href='http://localhost:4200' class='button'>🚀 Comenzar a Reservar</a>
                    </center>
                    
                    <hr style='margin: 30px 0; border: none; border-top: 1px solid #eee;'>
                    
                    <p>Si tienes alguna pregunta, no dudes en contactarnos. ¡Estamos aquí para ayudarte!</p>
                    
                    <p>¡Que disfrutes tu experiencia futbolera! ⚽</p>
                    
                    <p style='font-style: italic; color: #666;'>
                        Atentamente,<br>
                        <strong>El Equipo de La Canchita de los Pibes</strong>
                    </p>
                </div>
                
                <div class='footer'>
                    <p>&copy; " . date('Y') . " La Canchita de los Pibes. Todos los derechos reservados.</p>
                    <div class='social-links'>
                        <a href='#'>Facebook</a> | 
                        <a href='#'>Instagram</a> | 
                        <a href='#'>WhatsApp</a>
                    </div>
                    <p style='margin-top: 15px;'>
                        <small>Este email fue enviado automáticamente. Si no te registraste en nuestro sitio, puedes ignorar este mensaje.</small>
                    </p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
    
    /**
     * Genera texto plano para email de confirmación (fallback)
     */
    private function generarTextoConfirmacionRegistro($nombre, $apellido, $email) {
        return "
¡Bienvenido/a a La Canchita de los Pibes!

Hola $nombre $apellido,

¡Tu registro fue exitoso! Ya eres parte de nuestra comunidad futbolera.

Tu cuenta: $email

¿Qué puedes hacer ahora?
- Reservar canchas: Elige entre nuestras diferentes canchas disponibles
- Ver horarios: Consulta disponibilidad en tiempo real
- Gestionar reservas: Modifica o cancela tus reservas
- Contactanos: Cualquier duda o sugerencia

Visita nuestro sitio: http://localhost:4200

Si tienes alguna pregunta, no dudes en contactarnos.

¡Que disfrutes tu experiencia futbolera!

Atentamente,
El Equipo de La Canchita de los Pibes

---
© " . date('Y') . " La Canchita de los Pibes. Todos los derechos reservados.
Este email fue enviado automáticamente. Si no te registraste en nuestro sitio, puedes ignorar este mensaje.
        ";
    }
}

?>
