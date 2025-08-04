<?php

// Definir BASE_URL solo si no está definida
if (!defined('BASE_URL')) {
    $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    //$carpeta = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');// fija la ruta hasta la carpeta en donde esta el archivo que estoy usando o abriendo
    //$carpeta = '/Mis_Proyectos/IFTS12-LaCanchitaDeLosPibes';// XAMPP
     $carpeta = ''; // SIN subcarpeta// POR PHP - s LOCALHOST:8000
    define('BASE_URL', $protocolo . $host . $carpeta);
}

require_once __DIR__ . '/../Template/cors.php';

session_start();
unset($_SESSION['logged_in']);
session_destroy();

echo json_encode(['success' => true, 'message' => 'Sesión cerrada correctamente.']);
exit;
?>