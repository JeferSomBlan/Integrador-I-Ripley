<?php

// Incluir archivo de configuración de la base de datos
include_once 'bd_config.php';  // Asegúrate de que la ruta a 'bd_config.php' sea correcta

// Incluir Monolog para logging
require_once __DIR__ . '/../vendor/autoload.php';  // Asegúrate de que la ruta sea correcta para tu estructura de carpetas
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Crear el logger
$log = new Logger('conexion_log');
$logDir = __DIR__ . '/logs';

// Asegurarse de que el directorio de logs exista
if (!file_exists($logDir)) {
    mkdir($logDir, 0777, true);  // Crear la carpeta si no existe
}

// Configurar el handler para el archivo de log
$log->pushHandler(new StreamHandler($logDir . '/conexion.log', Logger::DEBUG)); // Registrar todo tipo de eventos

$cnx = '';  // Variable para la conexión

// Conexión a la base de datos
function conectar() {
    global $cnx, $log;

    // Intentamos la conexión a la base de datos utilizando las constantes definidas en 'bd_config.php'
    $cnx = mysqli_connect(HOST, USER, PASS, DATABASE, PORT);

    // Verificar si la conexión fue exitosa
    if (mysqli_connect_errno()) {
        // Registrar el error en el log
        $log->error('Error de conexión a la base de datos', [
            'error_code' => mysqli_connect_errno(),
            'error_message' => mysqli_connect_error()
        ]);
        // Mostrar el error y terminar el script
        die("Error de conexión: " . mysqli_connect_error());
    } else {
        // Registrar la conexión exitosa
        $log->info('Conexión exitosa a la base de datos', [
            'host' => HOST,
            'database' => DATABASE
        ]);
    }

    // Configurar el conjunto de caracteres
    mysqli_query($cnx, "set names utf8");
}

// Desconexión de la base de datos
function desconectar() {
    global $cnx, $log;

    // Verificamos si la conexión existe antes de intentar cerrarla
    if ($cnx) {
        mysqli_close($cnx);
        // Registrar la desconexión
        $log->info('Conexión cerrada con éxito');
    }
}

// Consultas a la Base de datos
function consultar($query) {
    global $cnx, $log;

    // Registrar la consulta SQL que se está ejecutando
    $log->debug('Ejecutando consulta', ['query' => $query]);

    $result = mysqli_query($cnx, $query);

    // Si la consulta no es válida, registrar el error y retornar un array vacío
    if (!$result) {
        $log->error('Error en la consulta SQL', [
            'query' => $query,
            'error_message' => mysqli_error($cnx)
        ]);
        return [];
    }

    $lista = array();
    while ($registro = mysqli_fetch_assoc($result)) {
        $lista[] = $registro;
    }

    mysqli_free_result($result);  // Liberar el resultado
    unset($registro);

    // Registrar el número de resultados obtenidos
    $log->debug('Consulta ejecutada con éxito', ['num_results' => count($lista)]);

    return $lista;
}

// Operaciones en la Base de datos
function ejecutar($query) {
    global $cnx, $log;

    // Registrar la operación SQL que se está ejecutando
    $log->debug('Ejecutando operación SQL', ['query' => $query]);

    $result = mysqli_query($cnx, $query);

    // Si la operación falla, registrar el error y retornar false
    if (!$result) {
        $log->error('Error en la operación SQL', [
            'query' => $query,
            'error_message' => mysqli_error($cnx)
        ]);
        return false;
    }

    // Registrar el éxito de la operación
    $log->debug('Operación SQL ejecutada con éxito');
    return $result;
}
