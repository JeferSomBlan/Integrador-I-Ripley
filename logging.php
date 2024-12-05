<?php
// logging.php

function log_message($message) {
    $log_file = 'logs/app.log'; // Archivo de log
    $date = date('Y-m-d H:i:s'); // Fecha y hora actual
    $log_message = "[$date] $message" . PHP_EOL;
    
    // Asegurarse de que el directorio de logs existe
    if (!file_exists('logs')) {
        mkdir('logs', 0777, true); // Crear la carpeta logs si no existe
    }
    
    // Escribir el mensaje en el archivo de log
    file_put_contents($log_file, $log_message, FILE_APPEND);
}
?>
