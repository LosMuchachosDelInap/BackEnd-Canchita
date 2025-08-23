<?php
require_once '../Template/cors.php';
require_once '../ConectionBD/CConection.php';

try {
    $conn = (new ConectionDB())->getConnection();
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id_usuario'])) {
        // Obtener reservas de un usuario específico
        $id_usuario = intval($_GET['id_usuario']);
        
        $sql = "SELECT r.*, c.nombreCancha, c.precio, h.horario, f.fecha, u.email, p.nombre, p.apellido, p.telefono
                FROM reserva r
                JOIN cancha c ON r.id_cancha = c.id_cancha
                JOIN horario h ON r.id_horario = h.id_horario
                JOIN fecha f ON r.id_fecha = f.id_fecha
                JOIN usuario u ON r.id_usuario = u.id_usuario
                LEFT JOIN persona p ON u.id_persona = p.id_persona
                WHERE r.id_usuario = ? AND r.habilitado = 1
                ORDER BY f.fecha DESC, h.horario DESC";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $reservas = [];
        while ($row = $result->fetch_assoc()) {
            // Asegurar que todos los valores son del tipo correcto
            $reservas[] = [
                'id_reserva' => (int)$row['id_reserva'],
                'id_usuario' => (int)$row['id_usuario'],
                'id_cancha' => (int)$row['id_cancha'],
                'id_fecha' => (int)$row['id_fecha'],
                'id_horario' => (int)$row['id_horario'],
                'idCreate' => $row['idCreate'],
                'idUpdate' => $row['idUpdate'],
                'habilitado' => (int)$row['habilitado'],
                'cancelado' => (int)$row['cancelado'],
                'nombreCancha' => $row['nombreCancha'],
                'precio' => (float)$row['precio'],
                'horario' => $row['horario'],
                'fecha' => $row['fecha'],
                'email' => $row['email'],
                'nombre' => $row['nombre'] ?? '',
                'apellido' => $row['apellido'] ?? '',
                'telefono' => $row['telefono'] ?? ''
            ];
        }
        
        // Asegurar que el JSON es válido
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($reservas, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Si no hay parámetros específicos, devolver array vacío
    echo json_encode([]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error del servidor: ' . $e->getMessage()]);
}
?>
                
            case 'PUT':
                if (preg_match('/cancelar\/(\d+)/', $path, $matches)) {
                    $this->cancelarReserva($matches[1]);
                }
                break;
                
            case 'DELETE':
                if (preg_match('/(\d+)$/', $path, $matches)) {
                    $this->eliminarReserva($matches[1]);
                }
                break;
                
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Método no permitido']);
        }
    }
    
    private function getCurrentUserId() {
        // TODO: Implementar autenticación JWT real
        // Por ahora retornamos un ID de prueba
        return 1;
    }
    
    public function getMisReservas($usuario_id) {
        try {
            $query = "SELECT 
                        r.id,
                        r.fecha,
                        r.hora_inicio,
                        r.hora_fin,
                        r.tipo_cancha,
                        r.cancha_id,
                        r.monto,
                        r.estado,
                        r.fecha_reserva,
                        r.observaciones,
                        u.nombre as usuario_nombre,
                        u.email as usuario_email
                      FROM reservas r
                      JOIN usuarios u ON r.usuario_id = u.id
                      WHERE r.usuario_id = :usuario_id
                      ORDER BY r.fecha DESC, r.hora_inicio DESC";
                      
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmt->execute();
            
            $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Formatear fechas para frontend
            foreach ($reservas as &$reserva) {
                $reserva['fecha'] = date('Y-m-d', strtotime($reserva['fecha']));
                $reserva['fecha_reserva'] = date('Y-m-d H:i:s', strtotime($reserva['fecha_reserva']));
            }
            
            echo json_encode([
                'success' => true,
                'data' => $reservas,
                'count' => count($reservas)
            ]);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Error al cargar reservas: ' . $e->getMessage()
            ]);
        }
    }
    
    public function crearReserva() {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            // Validar datos requeridos
            $required = ['fecha', 'hora_inicio', 'hora_fin', 'tipo_cancha', 'cancha_id', 'monto'];
            foreach ($required as $field) {
                if (!isset($input[$field]) || empty($input[$field])) {
                    throw new Exception("Campo requerido faltante: $field");
                }
            }
            
            // Verificar disponibilidad
            if (!$this->isDisponible($input['fecha'], $input['hora_inicio'], $input['hora_fin'], $input['cancha_id'])) {
                http_response_code(409);
                echo json_encode([
                    'success' => false,
                    'error' => 'La cancha no está disponible en el horario seleccionado'
                ]);
                return;
            }
            
            $usuario_id = $this->getCurrentUserId();
            
            $query = "INSERT INTO reservas (
                        usuario_id, 
                        fecha, 
                        hora_inicio, 
                        hora_fin, 
                        tipo_cancha, 
                        cancha_id, 
                        monto, 
                        estado, 
                        observaciones,
                        fecha_reserva
                      ) VALUES (
                        :usuario_id, 
                        :fecha, 
                        :hora_inicio, 
                        :hora_fin, 
                        :tipo_cancha, 
                        :cancha_id, 
                        :monto, 
                        'Pendiente', 
                        :observaciones,
                        NOW()
                      )";
                      
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':usuario_id', $usuario_id);
            $stmt->bindParam(':fecha', $input['fecha']);
            $stmt->bindParam(':hora_inicio', $input['hora_inicio']);
            $stmt->bindParam(':hora_fin', $input['hora_fin']);
            $stmt->bindParam(':tipo_cancha', $input['tipo_cancha']);
            $stmt->bindParam(':cancha_id', $input['cancha_id']);
            $stmt->bindParam(':monto', $input['monto']);
            $stmt->bindParam(':observaciones', $input['observaciones'] ?? '');
            
            if ($stmt->execute()) {
                $reserva_id = $this->connection->lastInsertId();
                
                // Obtener la reserva creada
                $query_select = "SELECT * FROM reservas WHERE id = :id";
                $stmt_select = $this->connection->prepare($query_select);
                $stmt_select->bindParam(':id', $reserva_id);
                $stmt_select->execute();
                
                $nueva_reserva = $stmt_select->fetch(PDO::FETCH_ASSOC);
                
                http_response_code(201);
                echo json_encode([
                    'success' => true,
                    'message' => 'Reserva creada exitosamente',
                    'data' => $nueva_reserva
                ]);
            } else {
                throw new Exception('Error al insertar la reserva en la base de datos');
            }
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Error al crear reserva: ' . $e->getMessage()
            ]);
        }
    }
    
    public function verificarDisponibilidad() {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            $required = ['fecha', 'hora_inicio', 'hora_fin', 'cancha_id'];
            foreach ($required as $field) {
                if (!isset($input[$field])) {
                    throw new Exception("Campo requerido: $field");
                }
            }
            
            $disponible = $this->isDisponible(
                $input['fecha'],
                $input['hora_inicio'],
                $input['hora_fin'],
                $input['cancha_id']
            );
            
            echo json_encode([
                'success' => true,
                'disponible' => $disponible
            ]);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    private function isDisponible($fecha, $hora_inicio, $hora_fin, $cancha_id) {
        $query = "SELECT COUNT(*) FROM reservas 
                  WHERE fecha = :fecha 
                  AND cancha_id = :cancha_id 
                  AND estado IN ('Pendiente', 'Confirmada')
                  AND (
                    (hora_inicio <= :hora_inicio AND hora_fin > :hora_inicio) OR
                    (hora_inicio < :hora_fin AND hora_fin >= :hora_fin) OR
                    (hora_inicio >= :hora_inicio AND hora_fin <= :hora_fin)
                  )";
                  
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':hora_inicio', $hora_inicio);
        $stmt->bindParam(':hora_fin', $hora_fin);
        $stmt->bindParam(':cancha_id', $cancha_id);
        $stmt->execute();
        
        return $stmt->fetchColumn() == 0;
    }
    
    public function cancelarReserva($reserva_id) {
        try {
            // Verificar que la reserva existe y pertenece al usuario
            $query_check = "SELECT id, estado FROM reservas WHERE id = :id AND usuario_id = :usuario_id";
            $stmt_check = $this->connection->prepare($query_check);
            $stmt_check->bindParam(':id', $reserva_id);
            $stmt_check->bindParam(':usuario_id', $this->getCurrentUserId());
            $stmt_check->execute();
            
            $reserva = $stmt_check->fetch(PDO::FETCH_ASSOC);
            
            if (!$reserva) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'error' => 'Reserva no encontrada'
                ]);
                return;
            }
            
            if ($reserva['estado'] === 'Cancelada') {
                echo json_encode([
                    'success' => false,
                    'error' => 'La reserva ya está cancelada'
                ]);
                return;
            }
            
            // Actualizar estado a cancelada
            $query = "UPDATE reservas SET estado = 'Cancelada' WHERE id = :id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $reserva_id);
            
            if ($stmt->execute()) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Reserva cancelada exitosamente'
                ]);
            } else {
                throw new Exception('Error al cancelar la reserva');
            }
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Error al cancelar reserva: ' . $e->getMessage()
            ]);
        }
    }
}

// Ejecutar la API
$api = new ReservaAPI();
$api->handleRequest();
?>
