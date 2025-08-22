<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost:4200');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once '../ConectionBD/CConection.php';

try {
    $conn = (new ConectionDB())->getConnection();
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Obtener todas las canchas activas
        $stmt = $conn->prepare("SELECT * FROM cancha WHERE habilitado = 1 AND cancelado = 0 ORDER BY nombreCancha ASC");
        $stmt->execute();
        $result = $stmt->get_result();
        
        $canchas = [];
        while ($row = $result->fetch_assoc()) {
            $canchas[] = [
                'id_cancha' => (int)$row['id_cancha'],
                'nombreCancha' => $row['nombreCancha'],
                'precio' => (float)$row['precio'],
                'habilitado' => (int)$row['habilitado'],
                'cancelado' => (int)$row['cancelado']
            ];
        }
        
        echo json_encode($canchas);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error del servidor: ' . $e->getMessage()]);
}
?>
