<?php
$sourceDir = realpath(__DIR__ . '/..'); // Directorio del proyecto
$backupDir = __DIR__ . '/backups';
if (!file_exists($backupDir)) {
    mkdir($backupDir, 0777, true); // Crear la carpeta si no existe
}

$backupFile = $backupDir . '/project_backup_' . date('Ymd_His') . '.zip';

$zip = new ZipArchive();
if ($zip->open($backupFile, ZipArchive::CREATE) === TRUE) {
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($sourceDir), RecursiveIteratorIterator::LEAVES_ONLY);
    foreach ($files as $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($sourceDir) + 1);
            $zip->addFile($filePath, $relativePath);
        }
    }
    $zip->close();
    echo "Backup del proyecto creado exitosamente: $backupFile";
} else {
    echo "Error al crear el backup del proyecto.";
}
