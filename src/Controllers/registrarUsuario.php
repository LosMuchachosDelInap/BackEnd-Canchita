<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../Model/Persona.php';
require_once __DIR__ . '/../Model/Usuario.php';
require_once __DIR__ . '/../Model/Empleado.php';
require_once __DIR__ . '/../ConectionBD/CConection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $nombre   = $data['nombre'] ?? '';
    $apellido = $data['apellido'] ?? '';
    $edad     = $data['edad'] ?? '';
    $dni      = $data['dni'] ?? '';
    $telefono = $data['telefono'] ?? '';
    $email    = $data['email'] ?? '';
    $clave    = $data['clave'] ?? '';
    $rol      = $data['rol'] ?? 6;

    $conn = (new ConectionDB())->getConnection();

    // Validar que el email no exista
    $stmt = $conn->prepare("SELECT id_usuario FROM usuario WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'El email ya está registrado.']);
        exit;
    }
    $stmt->close();

    // Crear persona
    $persona = new Persona($nombre, $apellido, $edad, $dni, $telefono);
    if (!$persona->guardar($conn)) {
        echo json_encode(['success' => false, 'message' => 'Error al registrar persona.']);
        exit;
    }

    // Crear usuario
    $usuario = new Usuario($email, $clave, $persona->getId());
    if (!$usuario->guardar($conn)) {
        echo json_encode(['success' => false, 'message' => 'Error al registrar usuario.']);
        exit;
    }

    // Crear empleado (si corresponde)
    $empleado = new Empleado($rol, $persona->getId(), $usuario->getId());
    if (!$empleado->guardar($conn)) {
        echo json_encode(['success' => false, 'message' => 'Error al registrar empleado.']);
        exit;
    }

    echo json_encode(['success' => true, 'message' => '¡Registro exitoso! Ya puedes ingresar.']);
    exit;
}
