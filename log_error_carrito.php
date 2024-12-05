<?php
// log_error_carrito.php

// Incluir autoload de Composer para Monolog (si no lo has hecho ya)
require_once 'vendor/autoload.php';

// Importar las clases necesarias de Monolog
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Crear el logger
$log = new Logger('carrito_log');

// Asegurarse de que el directorio de logs existe antes de escribir
$logDir = __DIR__ . '/logs';
if (!file_exists($logDir)) {
    mkdir($logDir, 0777, true); // Crear la carpeta si no existe
}

// Configurar el handler para escribir los logs de errores
$log->pushHandler(new StreamHandler($logDir . '/app.log', Logger::ERROR));

// Obtener los datos de la solicitud
$data = json_decode(file_get_contents('php://input'), true);

// Verificar si los datos son válidos y tienen el campo 'mensaje'
if ($data && isset($data['mensaje'])) {
    // Registrar el error en el log
    $log->error("Error en el carrito", ['mensaje' => $data['mensaje']]);
    
    // Responder indicando que el log fue registrado
    echo json_encode(['success' => true, 'message' => 'Log registrado correctamente']);
} else {
    // Responder si no se reciben los datos correctamente
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
}
?>
