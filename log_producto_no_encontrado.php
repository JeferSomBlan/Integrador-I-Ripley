<?php
// log_producto_no_encontrado.php

// Incluir autoload de Composer para Monolog
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

// Verificar si los datos son válidos y tienen el campo 'idProducto' y 'mensaje'
if ($data && isset($data['idProducto'])) {
    // Si el campo 'mensaje' no está presente, asignar un valor predeterminado
    $mensaje = isset($data['mensaje']) ? $data['mensaje'] : 'Mensaje no proporcionado';

    // Registrar el error en el log
    $log->error("Producto no encontrado", [
        'idProducto' => $data['idProducto'],
        'mensaje' => $mensaje
    ]);
    
    // Responder indicando que el log fue registrado
    echo json_encode(['success' => true, 'message' => 'Log registrado correctamente']);
} else {
    // Responder si no se reciben los datos correctamente
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
}
?>
