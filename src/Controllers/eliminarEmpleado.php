<?php
require_once __DIR__ . '/../Template/cors.php';
require_once __DIR__ . '/../ConectionBD/CConection.php';
require_once __DIR__ . '/../Model/peticionesSql.php';

class EliminarEmpleado {
    private $conn;
    public function __construct($conn = null) { if ($conn !== null) $this->setConn($conn); }
    public function setConn($conn) { $this->conn = $conn; }
    public function getConn() { return $this->conn; }
    public function deshabilitarPorId($idEmpleado) {
        global $eliminarEmpleado;
        $stmt = $this->getConn()->prepare($eliminarEmpleado);
        if ($stmt) {
            $stmt->bind_param("i", $idEmpleado);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        }
        return false;
    }
}

$conectarDB = new ConectionDB();
$conn = $conectarDB->getConnection();
$eliminador = new EliminarEmpleado();
$eliminador->setConn($conn);

$data = json_decode(file_get_contents('php://input'), true);
$idEmpleado = $data['id_empleado'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && $idEmpleado) {
    if ($eliminador->deshabilitarPorId($idEmpleado)) {
        echo json_encode(['success' => true, 'message' => 'Empleado eliminado con éxito']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar empleado']);
    }
    exit;
}
echo json_encode(['success' => false, 'message' => 'ID de empleado no especificado o método incorrecto']);
exit;