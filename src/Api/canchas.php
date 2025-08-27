<?php
require_once '../Template/cors.php';
require_once '../ConectionBD/CConection.php';

try {
    $conn = (new ConectionDB())->getConnection();
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Obtener todas las canchas activas con información completa
        $stmt = $conn->prepare("SELECT * FROM cancha WHERE habilitado = 1 AND cancelado = 0 ORDER BY nombreCancha ASC");
        $stmt->execute();
        $result = $stmt->get_result();
        
        $canchas = [];
        while ($row = $result->fetch_assoc()) {
            // Determinar tipo basado en el nombre (mejora futura: agregar columna tipo)
            $tipo = 'Fútbol';
            if (stripos($row['nombreCancha'], '5') !== false) {
                $tipo = 'Fútbol 5';
            } elseif (stripos($row['nombreCancha'], '7') !== false) {
                $tipo = 'Fútbol 7';
            } elseif (stripos($row['nombreCancha'], '11') !== false) {
                $tipo = 'Fútbol 11';
            }
            
            // Generar descripción basada en el tipo
            $descripcion = "Cancha de $tipo en excelente estado";
            if (stripos($row['nombreCancha'], 'sintético') !== false || stripos($row['nombreCancha'], 'sintetico') !== false) {
                $descripcion .= " con césped sintético";
            } elseif (stripos($row['nombreCancha'], 'natural') !== false) {
                $descripcion .= " con césped natural";
            }
            
            // Generar imagen SVG embedded
            $svgContent = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 250" style="background-color:#4CAF50">
                <rect x="20" y="20" width="360" height="210" fill="#2E7D32" stroke="#fff" stroke-width="2" rx="8"/>
                <line x1="200" y1="20" x2="200" y2="230" stroke="white" stroke-width="3"/>
                <circle cx="200" cy="125" r="40" fill="none" stroke="white" stroke-width="2"/>
                <circle cx="200" cy="125" r="3" fill="white"/>
                <rect x="20" y="80" width="25" height="90" fill="none" stroke="white" stroke-width="2"/>
                <rect x="355" y="80" width="25" height="90" fill="none" stroke="white" stroke-width="2"/>
                <text x="200" y="180" text-anchor="middle" font-family="Arial" font-size="14" fill="white" font-weight="bold">' . 
                htmlspecialchars($row['nombreCancha']) . '</text>
            </svg>';
            
            $imagenBase64 = 'data:image/svg+xml;base64,' . base64_encode($svgContent);
            
            // Características basadas en el tipo y nombre
            $caracteristicas = [
                ['icon' => 'grass', 'nombre' => 'Césped de calidad'],
                ['icon' => 'wb_sunny', 'nombre' => 'Iluminación LED'],
                ['icon' => 'local_parking', 'nombre' => 'Estacionamiento']
            ];
            
            // Añadir características específicas según el tipo
            if (stripos($row['nombreCancha'], 'vestuario') !== false) {
                $caracteristicas[] = ['icon' => 'wc', 'nombre' => 'Vestuarios'];
            }
            if (stripos($tipo, '11') !== false) {
                $caracteristicas[] = ['icon' => 'stadium', 'nombre' => 'Graderías'];
            }
            
            $canchas[] = [
                'id_cancha' => (int)$row['id_cancha'],
                'nombreCancha' => $row['nombreCancha'],
                'precio' => (float)$row['precio'],
                'habilitado' => (int)$row['habilitado'],
                'cancelado' => (int)$row['cancelado'],
                'tipo' => $tipo,
                'descripcion' => $descripcion,
                'imagen' => $imagenBase64,
                'caracteristicas' => $caracteristicas
            ];
        }
        
        // Agregar header para debugging
        header('Content-Type: application/json');
        echo json_encode($canchas);
        
    } else {
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error del servidor: ' . $e->getMessage()]);
}
?>
