<?php
// Configuración
$directories = [
    [
        'path' => __DIR__ . '/backups', // Directorio de backups
        'days' => 30 // Días de antigüedad para eliminar
    ],
    [
        'path' => __DIR__ . '../logs', // Directorio de logs
        'days' => 15 // Días de antigüedad para eliminar
    ],
];
$deletedFiles = [];
$errors = [];

// Función para limpiar archivos antiguos
function cleanOldFiles($directory, $days) {
    global $deletedFiles, $errors;

    if (!is_dir($directory)) {
        $errors[] = "El directorio no existe: $directory";
        return;
    }

    $files = glob($directory . '/*'); // Obtener todos los archivos en el directorio
    $now = time();
    $timeLimit = $days * 24 * 60 * 60; // Convertir días a segundos

    foreach ($files as $file) {
        if (is_file($file)) {
            $fileModifiedTime = filemtime($file);
            if ($now - $fileModifiedTime > $timeLimit) {
                if (unlink($file)) {
                    $deletedFiles[] = $file;
                } else {
                    $errors[] = "No se pudo eliminar el archivo: $file";
                }
            }
        }
    }
}

// Ejecutar limpieza en los directorios configurados
foreach ($directories as $config) {
    cleanOldFiles($config['path'], $config['days']);
}

// Resultado
if (!empty($deletedFiles)) {
    echo "Archivos eliminados:\n";
    foreach ($deletedFiles as $file) {
        echo " - $file\n";
    }
} else {
    echo "No se encontraron archivos para eliminar.\n";
}

if (!empty($errors)) {
    echo "Errores encontrados:\n";
    foreach ($errors as $error) {
        echo " - $error\n";
    }
}
?>
