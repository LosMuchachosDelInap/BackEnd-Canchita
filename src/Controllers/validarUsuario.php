<?php

require_once __DIR__ . '/../Model/Usuario.php';
require_once __DIR__ . '/../Model/Empleado.php';
require_once __DIR__ . '/../ConectionBD/CConection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $email = trim($data['email'] ?? '');
    $clave = trim($data['clave'] ?? '');
    $conn = (new ConectionDB())->getConnection();

    $stmt = $conn->prepare("SELECT u.email, u.clave, u.id_usuario, u.id_persona, e.id_rol, r.rol
        FROM usuario u
        JOIN empleado e ON u.id_usuario = e.id_usuario
        JOIN roles r ON e.id_rol = r.id_roles
        WHERE u.email = ? AND u.habilitado = 1 AND u.cancelado = 0");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($fila = $resultado->fetch_assoc()) {
        $usuarioObj = new Usuario($fila['email'], $fila['clave'], $fila['id_persona'], $fila['id_usuario'], false);
        if ($usuarioObj->verificarClave($clave)) {
            // Login correcto
            echo json_encode([
                'success' => true,
                'message' => 'Login correcto',
                'user' => [
                    'email' => $fila['email'],
                    'id_usuario' => $fila['id_usuario'],
                    'id_rol' => $fila['id_rol'],
                    'nombre_rol' => $fila['rol']
                ]
            ]);
            exit;
        }
    }
    // Login incorrecto
    echo json_encode([
        'success' => false,
        'message' => 'Usuario o contraseña incorrectos'
    ]);
    exit;
}
