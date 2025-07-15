<?php
// Script para verificar la sintaxis de todos los archivos PHP del proyecto
echo "Verificando sintaxis de archivos PHP...\n";
echo "=====================================\n";

function checkPhpSyntax($file) {
    $output = [];
    $returnCode = 0;
    
    exec("php -l \"$file\" 2>&1", $output, $returnCode);
    
    if ($returnCode === 0) {
        echo "✓ $file - OK\n";
        return true;
    } else {
        echo "✗ $file - ERROR:\n";
        foreach ($output as $line) {
            echo "  $line\n";
        }
        return false;
    }
}

// Archivos principales
$mainFiles = [
    'index.php',
    'test_mail.php',
    'test_reserva_completa.php',
    'verificar_bd.php',
    'diagnostico_correos.php',
    'pasosParaDiagnosticoCompleto.php'
];

echo "Verificando archivos principales:\n";
echo "---------------------------------\n";
foreach ($mainFiles as $file) {
    if (file_exists($file)) {
        checkPhpSyntax($file);
    } else {
        echo "- $file - No encontrado\n";
    }
}

// Verificar archivos en src/
echo "\nVerificando archivos en src/:\n";
echo "----------------------------\n";

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator('src/', RecursiveDirectoryIterator::SKIP_DOTS)
);

$phpFiles = [];
foreach ($iterator as $file) {
    if ($file->getExtension() === 'php') {
        $phpFiles[] = $file->getPathname();
    }
}

sort($phpFiles);

foreach ($phpFiles as $file) {
    checkPhpSyntax($file);
}

echo "\n=====================================\n";
echo "Verificación completa\n";
?>
