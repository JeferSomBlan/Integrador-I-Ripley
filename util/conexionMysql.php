<?php

// Incluir archivo de configuración de la base de datos
include_once 'bd_config.php';  // Asegúrate de que la ruta sea correcta

// Incluir Monolog para logging
require_once __DIR__ . '/../vendor/autoload.php';
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Crear el logger
$log = new Logger('conexion_log');
$logDir = __DIR__ . '/logs';

// Asegurarse de que el directorio de logs exista y sea accesible
if (!file_exists($logDir)) {
    if (!mkdir($logDir, 0777, true)) {
        die("No se pudo crear el directorio de logs: $logDir");
    }
}

// Configurar el handler para el archivo de log
$logFile = $logDir . '/conexion.log';
$log->pushHandler(new StreamHandler($logFile, Logger::DEBUG)); // Registrar todo tipo de eventos

$cnx = null;  // Variable para la conexión

// Conexión a la base de datos
function conectar() {
    global $cnx, $log;

    // Intentar la conexión a la base de datos
    $cnx = @mysqli_connect(HOST, USER, PASS, DATABASE, PORT);

    if (!$cnx) {
        // Registrar el error de conexión
        $log->error('Error de conexión a la base de datos', [
            'error_code' => mysqli_connect_errno(),
            'error_message' => mysqli_connect_error()
        ]);
        die("Error de conexión: " . mysqli_connect_error());
    } else {
        // Registrar conexión exitosa
        $log->info('Conexión exitosa a la base de datos', [
            'host' => HOST,
            'database' => DATABASE
        ]);
    }

    // Configurar el conjunto de caracteres
    if (!mysqli_set_charset($cnx, 'utf8')) {
        $log->warning('No se pudo establecer el conjunto de caracteres UTF-8', [
            'error_message' => mysqli_error($cnx)
        ]);
    }
}

// Desconexión de la base de datos
function desconectar() {
    global $cnx, $log;

    if ($cnx) {
        mysqli_close($cnx);
        $log->info('Conexión cerrada con éxito');
    } else {
        $log->warning('Intento de desconexión sin una conexión activa');
    }
}

// Consultas a la Base de datos
function consultar($query) {
    global $cnx, $log;

    if (!$cnx) {
        $log->error('No se puede ejecutar consulta sin una conexión activa', ['query' => $query]);
        return [];
    }

    $log->debug('Ejecutando consulta', ['query' => $query]);

    $result = @mysqli_query($cnx, $query);

    if (!$result) {
        $log->error('Error en la consulta SQL', [
            'query' => $query,
            'error_message' => mysqli_error($cnx)
        ]);
        return [];
    }

    $lista = [];
    while ($registro = mysqli_fetch_assoc($result)) {
        $lista[] = $registro;
    }

    mysqli_free_result($result);

    $log->info('Consulta ejecutada con éxito', ['num_results' => count($lista)]);
    return $lista;
}

// Operaciones en la Base de datos
function ejecutar($query) {
    global $cnx, $log;

    if (!$cnx) {
        $log->error('No se puede ejecutar operación sin una conexión activa', ['query' => $query]);
        return false;
    }

    $log->debug('Ejecutando operación SQL', ['query' => $query]);

    $result = @mysqli_query($cnx, $query);

    if (!$result) {
        $log->error('Error en la operación SQL', [
            'query' => $query,
            'error_message' => mysqli_error($cnx)
        ]);
        return false;
    }

    $log->info('Operación SQL ejecutada con éxito');
    return $result;
}
