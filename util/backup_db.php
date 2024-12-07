<?php
// backup_db.php

// Incluir dependencias necesarias
require_once 'conexionMysql.php'; // Asegúrate de que la ruta sea correcta

// Establecer la zona horaria
date_default_timezone_set("America/Lima");

// Configuración del backup
$backupDir = __DIR__ . '/backups'; // Carpeta donde se guardarán los backups
$backupFile = $backupDir . '/db_backup_' . date('Ymd_His') . '.sql'; // Nombre del archivo de backup

// Verificar si el directorio de backups existe, si no, crearlo
if (!file_exists($backupDir)) {
    mkdir($backupDir, 0777, true);
}

// Comando para realizar el backup
$command = "C:/xampp/mysql/bin/mysqldump --user=" . USER . " --password=" . PASS . " --host=" . HOST . " " . DATABASE . " > " . escapeshellarg($backupFile);

// Ejecutar el comando
exec($command . " 2>&1", $output, $result);

if ($result === 0) {
    echo "Backup de base de datos creado exitosamente: $backupFile";
} else {
    // Mostrar el error si el comando falla
    echo "Error al crear el backup de base de datos.\n";
    echo "Comando ejecutado: $command\n";
    echo "Salida del comando: " . implode("\n", $output) . "\n";
    echo "Código de error: $result\n";
}
