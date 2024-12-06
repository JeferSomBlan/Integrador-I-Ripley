<?php
// Incluir dependencias necesarias
require_once 'conexionMysql.php';

require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Monolog\Logger;

// Establecer el encabezado de respuesta
header('Content-Type: application/json');

$response = [
    'status' => 'OK',
    'checks' => [],
    'timestamp' => date('Y-m-d H:i:s'),
];

// 1. Verificar conectividad con la base de datos
try {
    conectar(); // Intentar la conexión a la base de datos
    $response['checks']['database'] = [
        'status' => 'OK',
        'message' => 'Conexión exitosa a la base de datos',
    ];
    desconectar(); // Cerrar la conexión
} catch (Exception $e) {
    $response['status'] = 'FAIL';
    $response['checks']['database'] = [
        'status' => 'FAIL',
        'message' => $e->getMessage(),
    ];
}

// 2. Verificar disponibilidad de espacio en disco
$diskFree = disk_free_space(__DIR__);
$diskTotal = disk_total_space(__DIR__);
$diskUsage = ($diskTotal - $diskFree) / $diskTotal * 100;

$response['checks']['disk'] = [
    'status' => $diskUsage > 90 ? 'WARN' : 'OK',
    'message' => sprintf('Uso de disco: %.2f%%', $diskUsage),
    'free_space' => sprintf('%.2f GB', $diskFree / 1024 / 1024 / 1024),
    'total_space' => sprintf('%.2f GB', $diskTotal / 1024 / 1024 / 1024),
];

// 3. Verificar carga del servidor (adaptado para Windows)
if (PHP_OS_FAMILY === 'Windows') {
    $cpuLoad = shell_exec("wmic cpu get loadpercentage");
    $cpuLoad = preg_replace('/[^0-9]/', '', $cpuLoad); // Filtrar números
    $response['checks']['server_load'] = [
        'status' => $cpuLoad && $cpuLoad > 80 ? 'WARN' : 'OK',
        'message' => $cpuLoad ? "Carga de CPU: {$cpuLoad}%" : "No se puede obtener la carga del servidor en este sistema",
    ];
} elseif (function_exists('sys_getloadavg')) {
    $load = sys_getloadavg();
    $response['checks']['server_load'] = [
        'status' => max($load) > 5 ? 'WARN' : 'OK',
        'message' => sprintf('Carga del servidor: 1min=%.2f, 5min=%.2f, 15min=%.2f', $load[0], $load[1], $load[2]),
    ];
} else {
    $response['checks']['server_load'] = [
        'status' => 'UNKNOWN',
        'message' => 'No se puede obtener la carga del servidor en este sistema',
    ];
}

// 4. Verificar disponibilidad de dependencias críticas
$dependencies = [
    'PHPMailer' => class_exists(PHPMailer::class),
    'Monolog' => class_exists(Logger::class),
];

foreach ($dependencies as $dependency => $isAvailable) {
    $response['checks'][$dependency] = [
        'status' => $isAvailable ? 'OK' : 'FAIL',
        'message' => $isAvailable ? "$dependency está disponible" : "$dependency no está disponible",
    ];

    if (!$isAvailable) {
        $response['status'] = 'FAIL';
    }
}

// 5. Verificar integridad del proyecto
$requiredFiles = ['conexionMysql.php', '../vendor/autoload.php'];
foreach ($requiredFiles as $file) {
    $exists = file_exists($file);
    $response['checks']['files'][] = [
        'file' => $file,
        'status' => $exists ? 'OK' : 'FAIL',
        'message' => $exists ? 'Archivo disponible' : 'Archivo no encontrado',
    ];

    if (!$exists) {
        $response['status'] = 'FAIL';
    }
}

// Responder con el estado general
http_response_code($response['status'] === 'OK' ? 200 : 500);
echo json_encode($response, JSON_PRETTY_PRINT);
