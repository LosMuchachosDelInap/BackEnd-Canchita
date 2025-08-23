<?php
require_once '../Template/cors.php';
require_once '../ConectionBD/CConection.php';

try {
    $conn = (new ConectionDB())->getConnection();
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Obtener todos los horarios activos
        $stmt = $conn->prepare("SELECT * FROM horario WHERE habilitado = 1 AND cancelado = 0 ORDER BY horario ASC");
        $stmt->execute();
        $result = $stmt->get_result();
        
        $horarios = [];
        while ($row = $result->fetch_assoc()) {
            $horarios[] = [
                'id_horario' => (int)$row['id_horario'],
                'horario' => $row['horario'],
                'habilitado' => (int)$row['habilitado'],
                'cancelado' => (int)$row['cancelado']
            ];
        }
        
        echo json_encode($horarios);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error del servidor: ' . $e->getMessage()]);
}
?>
