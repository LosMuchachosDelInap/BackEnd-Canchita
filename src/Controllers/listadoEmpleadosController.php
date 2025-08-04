<?php
require_once __DIR__ . '/../Template/cors.php';
require_once __DIR__ . '/../Model/Empleado.php';
require_once __DIR__ . '/../ConectionBD/CConection.php';

$conn = (new ConectionDB())->getConnection();
$empleados = Empleado::listarTodos($conn);

// Traer roles para el select
$roles = [];
$result = $conn->query("SELECT id_roles, rol FROM roles");
while ($row = $result->fetch_assoc()) {
    $roles[] = $row;
}

echo json_encode([
    'success' => true,
    'empleados' => $empleados,
    'roles' => $roles
]);
exit;