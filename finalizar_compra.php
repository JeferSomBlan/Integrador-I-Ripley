// finalizar_compra.php
<?php
include_once './util/conexionMysql.php';

// Incluir Monolog
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Crear el logger
$log = new Logger('finalizar_compra');
$log->pushHandler(new StreamHandler('./logs/app.log', Logger::INFO));

$data = json_decode(file_get_contents('php://input'), true);

if ($data && isset($data['carrito'])) {
    try {
        conectar(); // Establecer conexión con la base de datos
        
        // Registrar log para inicio de procesamiento
        $log->info("Inicio del procesamiento de la compra. Carrito: " . json_encode($data['carrito']));
        
        foreach ($data['carrito'] as $item) {
            $sql = "UPDATE productos SET stock = stock - {$item['cantidad']} WHERE id = {$item['id']}";
            ejecutar($sql);
            
            // Registrar log después de actualizar el stock
            $log->info("Producto con ID {$item['id']} actualizado. Cantidad: {$item['cantidad']}");
        }
        
        desconectar(); // Desconectar de la base de datos
        
        // Registrar log para finalizar el proceso de compra
        $log->info("Compra finalizada con éxito.");
        
        echo json_encode(['success' => true, 'message' => 'Compra finalizada']);
    } catch (Exception $e) {
        // Registrar log de error en caso de excepción
        $log->error("Error al procesar la compra: " . $e->getMessage());
        
        echo json_encode(['success' => false, 'message' => 'Error al procesar la compra']);
    }
}
?>
