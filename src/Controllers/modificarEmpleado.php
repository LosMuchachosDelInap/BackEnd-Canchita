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

session_start();
require_once __DIR__ . '/../Template/cors.php';
require_once __DIR__ . '/../Model/Empleado.php';
require_once __DIR__ . '/../Model/Persona.php';
require_once __DIR__ . '/../Model/Usuario.php';
require_once __DIR__ . '/../ConectionBD/CConection.php';

$conn = (new ConectionDB())->getConnection();

$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $data) {
    $idPersona = $data['id_persona'] ?? null;
    $idUsuario = $data['id_usuario'] ?? null;
    $idEmpleado = $data['id_empleado'] ?? null;
    $idRol = $data['rol'] ?? null;
    $nombre = $data['nombre'] ?? null;
    $apellido = $data['apellido'] ?? null;
    $edad = $data['edad'] ?? null;
    $dni = $data['dni'] ?? null;
    $telefono = $data['telefono'] ?? null;
    $email = $data['usuario'] ?? null;
    $clave = $data['clave'] ?? null;

    // Actualizar persona
    $persona = Persona::buscarPorId($conn, $idPersona);
    if ($persona) {
        $persona->setNombre($nombre);
        $persona->setApellido($apellido);
        $persona->setEdad($edad);
        $persona->setDni($dni);
        $persona->setTelefono($telefono);
        $persona->actualizar($conn); 
    }

    // Actualizar usuario
    $usuario = Usuario::buscarPorId($conn, $idUsuario);
    if ($usuario) {
        $usuario->setEmail($email);
        if (!empty($clave)) {
            $usuario->setClave($clave); // Hashea internamente
        }
        $usuario->actualizar($conn); 
    }

    // Actualizar empleado
    $empleado = Empleado::buscarPorId($conn, $idEmpleado);
    if ($empleado) {
        $empleado->setIdRol($idRol);
        $empleado->actualizar($conn); 
    }

    echo json_encode(['success' => true, 'message' => 'Empleado modificado correctamente']);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Datos inválidos o método incorrecto']);
exit;