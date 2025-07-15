#!/bin/bash

# Script para verificar la sintaxis de todos los archivos PHP
echo "Verificando sintaxis de archivos PHP..."
echo "======================================="

# Función para verificar un archivo
check_file() {
    local file=$1
    echo "Verificando: $file"
    php -l "$file" 2>&1 | grep -v "No syntax errors detected" || echo "✓ $file - OK"
}

# Verificar archivos principales
check_file "index.php"
check_file "test_mail.php"
check_file "test_reserva_completa.php"
check_file "verificar_bd.php"
check_file "diagnostico_correos.php"
check_file "pasosParaDiagnosticoCompleto.php"

# Verificar archivos en src/
find src/ -name "*.php" -type f | while read file; do
    check_file "$file"
done

echo "======================================="
echo "Verificación completa"
