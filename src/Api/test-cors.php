<?php
// Archivo de prueba para verificar CORS
require_once __DIR__ . '/../Template/cors.php';

// Respuesta simple para verificar conectividad
echo json_encode([
    'success' => true,
    'message' => 'Conexión CORS exitosa',
    'timestamp' => date('Y-m-d H:i:s'),
    'server_info' => [
        'method' => $_SERVER['REQUEST_METHOD'],
        'origin' => $_SERVER['HTTP_ORIGIN'] ?? 'No origin header',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'No user agent'
    ]
]);
?>
