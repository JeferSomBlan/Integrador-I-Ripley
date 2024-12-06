<?php
include_once './util/conexionMysql.php';

// Incluir Sentry
require_once './vendor/autoload.php';
\Sentry\init([
    'dsn' => 'https://50546abde49ec9c76f7562058fe9d492@o4508412475277312.ingest.us.sentry.io/4508417566638080',
    'traces_sample_rate' => 1.0, // Registrar transacciones
]);

/**
 * Función para obtener los productos desde la base de datos
 * con manejo de errores mediante Sentry.
 */
function obtenerProductos() {
    try {
        conectar();

        // Registrar un mensaje en Sentry para el inicio de la operación
        \Sentry\captureMessage("Inicio de obtención de productos", \Sentry\Severity::info());

        $sql = "SELECT id, nombre, descripcion, precio, descuento, stock, imagen_url FROM productos";
        $productos = consultar($sql); // Ejecuta la consulta y devuelve el resultado

        // Registrar éxito de la consulta si se obtuvieron productos
        if ($productos && is_array($productos)) {
            \Sentry\captureMessage("Productos obtenidos correctamente. Cantidad: " . count($productos), \Sentry\Severity::info());
            return $productos;
        } else {
            // Registrar advertencia en Sentry si no se obtuvieron productos
            \Sentry\captureMessage("No se encontraron productos en la base de datos.", \Sentry\Severity::warning());
            return [];
        }
    } catch (Exception $e) {
        // Capturar cualquier excepción y registrarla en Sentry
        \Sentry\captureException($e);
        return [];
    } finally {
        // Asegurarse de cerrar la conexión
        desconectar();
    }
}

// Establecer encabezados para JSON y control de caché
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: 0');

try {
    $productos = obtenerProductos();

    // Validar si hubo algún problema en la obtención de productos
    if (empty($productos)) {
        // Registrar advertencia si no se enviará ningún producto
        \Sentry\captureMessage("Respuesta enviada con un array vacío de productos", \Sentry\Severity::warning());
    }

    // Convertir el resultado a JSON y enviarlo
    echo json_encode($productos);
} catch (Exception $e) {
    // Capturar errores al enviar la respuesta
    \Sentry\captureException($e);

    // Enviar una respuesta de error en JSON
    http_response_code(500);
    echo json_encode(['error' => 'Ocurrió un error al procesar la solicitud.']);
}
?>
