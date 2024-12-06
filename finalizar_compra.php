<?php
// finalizar_compra.php
session_start();
include_once './util/conexionMysql.php';

// Incluir Sentry y Monolog
require_once './vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Sentry\Severity;

// Inicializar Sentry
\Sentry\init(['dsn' => 'https://50546abde49ec9c76f7562058fe9d492@o4508412475277312.ingest.us.sentry.io/4508417566638080']);

// Crear el logger de Monolog
$log = new Logger('finalizar_compra');
$log->pushHandler(new StreamHandler('./logs/app.log', Logger::INFO));

// Leer los datos enviados por el cliente
$data = json_decode(file_get_contents('php://input'), true);

if ($data && isset($data['carrito'])) {
    try {
        conectar(); // Establecer conexión con la base de datos

        // Registrar log en Sentry para el inicio del proceso
        \Sentry\captureMessage("Inicio del procesamiento de la compra. Carrito: " . json_encode($data['carrito']), Severity::info());

        // Registrar log en Monolog
        $log->info("Inicio del procesamiento de la compra.", ['carrito' => $data['carrito']]);

        foreach ($data['carrito'] as $item) {
            if (!isset($item['id'], $item['cantidad'])) {
                throw new Exception("Datos del carrito incompletos: " . json_encode($item));
            }

            // Validar cantidad para evitar inconsistencias
            if ($item['cantidad'] <= 0) {
                throw new Exception("Cantidad inválida para el producto ID {$item['id']}: {$item['cantidad']}");
            }

            // Actualizar el stock en la base de datos
            $sql = "UPDATE productos SET stock = stock - {$item['cantidad']} WHERE id = {$item['id']}";
            ejecutar($sql);

            // Registrar log después de actualizar el stock
            $log->info("Producto con ID {$item['id']} actualizado. Cantidad: {$item['cantidad']}");
            \Sentry\captureMessage("Producto actualizado. ID: {$item['id']}, Cantidad: {$item['cantidad']}", Severity::info());
        }

        desconectar(); // Desconectar de la base de datos

        // Log de éxito en Monolog y Sentry
        $log->info("Compra finalizada con éxito.");
        \Sentry\captureMessage("Compra finalizada con éxito.", Severity::info());

        echo json_encode(['success' => true, 'message' => 'Compra finalizada']);
    } catch (Exception $e) {
        // Capturar y registrar excepciones en Sentry
        \Sentry\captureException($e);

        // Registrar log de error en Monolog
        $log->error("Error al procesar la compra: " . $e->getMessage());

        echo json_encode(['success' => false, 'message' => 'Error al procesar la compra']);
    }
} else {
    // Si no se envían datos o el carrito está vacío
    $mensaje_error = "Datos de la compra no válidos o carrito vacío.";
    \Sentry\captureMessage($mensaje_error, Severity::warning());
    $log->warning($mensaje_error);

    echo json_encode(['success' => false, 'message' => $mensaje_error]);
}
?>
