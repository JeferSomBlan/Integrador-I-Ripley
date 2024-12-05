<?php
// Incluir el autoload de Composer para Monolog
require_once '../vendor/autoload.php';

// Usar las clases de Monolog
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Establecer la zona horaria de Perú
date_default_timezone_set('America/Lima');

// Crear el logger
$log = new Logger('logout_log');
$logDir = __DIR__ . '/logs';

// Asegurarse de que el directorio de logs exista
if (!file_exists($logDir)) {
    mkdir($logDir, 0777, true);  // Crear la carpeta si no existe
}

// Configurar el handler para el archivo de log
$log->pushHandler(new StreamHandler($logDir . '/logout.log', Logger::INFO)); // Registrar todo tipo de eventos

// Iniciar la sesión
session_start();

// Verificar si el nombre de usuario existe en la sesión
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'desconocido';

// Registrar el evento de cierre de sesión
$log->info('Cierre de sesión realizado', [
    'usuario' => $username,
    'hora' => date('Y-m-d H:i:s')
]);

// Destruir la sesión
session_unset();
session_destroy();

// Redirigir a la página de inicio
header("Location: ../index.php");
exit();
