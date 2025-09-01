<?php
// Script de prueba para el EmailService

require_once __DIR__ . '/EmailService.php';

echo "🧪 Probando EmailService...\n\n";

try {
    $emailService = new EmailService();
    
    // Datos de prueba
    $email = 'test@example.com';
    $nombre = 'Juan';
    $apellido = 'Pérez';
    
    echo "📧 Intentando enviar email de confirmación a: $email\n";
    echo "👤 Nombre: $nombre $apellido\n\n";
    
    $resultado = $emailService->enviarConfirmacionRegistro($email, $nombre, $apellido);
    
    if ($resultado['success']) {
        echo "✅ SUCCESS: " . $resultado['message'] . "\n";
    } else {
        echo "❌ ERROR: " . $resultado['message'] . "\n";
    }
    
} catch (Exception $e) {
    echo "💥 EXCEPCIÓN: " . $e->getMessage() . "\n";
}

echo "\n📄 Revisa el log en logs/mail_debug.log para más detalles.\n";

?>
