<?php
// Migración para agregar firebase_uid a la tabla usuario
require_once 'CConection.php';

try {
    $conn = (new ConectionDB())->getConnection();
    
    // Verificar si la columna firebase_uid ya existe
    $result = $conn->query("SHOW COLUMNS FROM usuario LIKE 'firebase_uid'");
    
    if ($result->num_rows == 0) {
        // La columna no existe, agregarla
        $sql = "ALTER TABLE usuario ADD COLUMN firebase_uid VARCHAR(255) NULL UNIQUE";
        
        if ($conn->query($sql) === TRUE) {
            echo "Columna firebase_uid agregada exitosamente\n";
        } else {
            echo "Error al agregar la columna firebase_uid: " . $conn->error . "\n";
        }
    } else {
        echo "La columna firebase_uid ya existe\n";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "Error de conexión: " . $e->getMessage() . "\n";
}
?>
