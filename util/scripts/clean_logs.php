<?php
// Ruta de la carpeta de logs
$logDir = __DIR__ . '/logs/';

// Número de días antes de eliminar logs antiguos
$dias = 30;
$limiteTiempo = time() - ($dias * 24 * 60 * 60);

if (is_dir($logDir)) {
    $files = scandir($logDir);
    foreach ($files as $file) {
        $filePath = $logDir . $file;
        if (is_file($filePath) && filemtime($filePath) < $limiteTiempo) {
            unlink($filePath);
            echo "Archivo eliminado: $filePath\n";
        }
    }
    echo "Limpieza de logs completada.\n";
} else {
    echo "Directorio de logs no encontrado.\n";
}
?>
