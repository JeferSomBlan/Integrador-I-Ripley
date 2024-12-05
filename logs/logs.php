<?php
// Asegúrate de incluir el autoloader de Composer
require_once 'vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Crear un canal (logger)
$log = new Logger('nombre_del_proyecto');

// Asegúrate de que la ruta sea correcta. Esto asume que logs.php y logs están en el mismo directorio
$logFilePath = __DIR__ . '/logs/app.log';

// Verificar si el archivo de log se puede crear o escribir en él
if (!is_writable(dirname($logFilePath))) {
    die('La carpeta de logs no tiene permisos de escritura.');
}

// Agregar un handler para guardar los logs en un archivo
$log->pushHandler(new StreamHandler($logFilePath, Logger::DEBUG));

// Ejemplo de logs con diferentes niveles de severidad
$log->debug('Esto es un mensaje de depuración.');
$log->info('El sistema se está ejecutando correctamente.');
$log->warning('Se produjo una advertencia en el sistema.');
$log->error('Se produjo un error en la base de datos.');
$log->emergency('¡Error crítico! El sistema no está funcionando.');
