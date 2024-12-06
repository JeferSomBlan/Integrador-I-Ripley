<?php
session_start();
include_once '../util/conexionMysql.php';

// Incluir Sentry y Monolog
require_once '../vendor/autoload.php';
\Sentry\init(['dsn' => 'https://50546abde49ec9c76f7562058fe9d492@o4508412475277312.ingest.us.sentry.io/4508417566638080']);
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Crear el logger
$log = new Logger('perfil_log');
$logDir = __DIR__ . '/logs';

// Asegurarse de que el directorio de logs exista
if (!file_exists($logDir)) {
    mkdir($logDir, 0777, true);
}
$log->pushHandler(new StreamHandler($logDir . '/perfil.log', Logger::INFO));

// Verificar que el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Generar un token CSRF si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validar token CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            throw new Exception("Solicitud no válida: CSRF token inválido.");
        }

        // Obtener el ID de usuario de la sesión
        $user_id = $_SESSION['user_id'];

        // Limpiar y sanitizar las entradas
        $nombre = trim(htmlspecialchars($_POST['nombre'], ENT_QUOTES, 'UTF-8'));
        $telefono_raw = trim(htmlspecialchars($_POST['telefono'], ENT_QUOTES, 'UTF-8'));
        $telefono = preg_replace('/\D/', '', $telefono_raw); // Eliminar caracteres no numéricos
        $direccion = trim(htmlspecialchars($_POST['direccion'], ENT_QUOTES, 'UTF-8'));
        $correo = trim(htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8'));

        // Validaciones
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("El correo electrónico no es válido.");
        }

        if (!preg_match('/^\d{9,15}$/', $telefono)) {
            throw new Exception("El número de teléfono no es válido.");
        }

        if (strlen($direccion) < 5) {
            throw new Exception("La dirección es demasiado corta.");
        }

        // Conectar a la base de datos
        conectar();

        // Obtener la información actual del usuario
        $sql_select = "SELECT nombre, telefono, direccion, correo FROM usuarios WHERE id = ?";
        $stmt = mysqli_prepare($cnx, $sql_select);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $nombre_actual, $telefono_actual, $direccion_actual, $correo_actual);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        // Registrar solo los cambios
        $cambios = [];
        if ($nombre_actual !== $nombre) {
            $cambios[] = "Nombre: de '$nombre_actual' a '$nombre'";
        }
        if ($telefono_actual !== $telefono) {
            $cambios[] = "Teléfono: de '$telefono_actual' a '$telefono'";
        }
        if ($direccion_actual !== $direccion) {
            $cambios[] = "Dirección: de '$direccion_actual' a '$direccion'";
        }
        if ($correo_actual !== $correo) {
            $cambios[] = "Correo: de '$correo_actual' a '$correo'";
        }

        // Si hay cambios, registrar en el log y en Sentry
        if (!empty($cambios)) {
            $log->info('Actualización de perfil', [
                'usuario' => $user_id,
                'cambios' => implode(", ", $cambios),
                'hora' => date('Y-m-d H:i:s')
            ]);
            \Sentry\captureMessage("Usuario $user_id actualizó su perfil: " . implode(", ", $cambios));
        }

        // Consulta preparada para actualizar el perfil
        $sql_update = "UPDATE usuarios SET nombre = ?, telefono = ?, direccion = ?, correo = ? WHERE id = ?";
        $stmt = mysqli_prepare($cnx, $sql_update);
        mysqli_stmt_bind_param($stmt, "ssssi", $nombre, $telefono, $direccion, $correo, $user_id);

        // Ejecutar la consulta
        if (mysqli_stmt_execute($stmt)) {
            // Regenerar el ID de sesión tras cambios sensibles
            session_regenerate_id(true);
            header("Location: mi_cuenta.php?actualizado=1");
        } else {
            throw new Exception("Error al actualizar el perfil en la base de datos.");
        }

        mysqli_stmt_close($stmt);
        desconectar();
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        conectar();
        $sql_select = "SELECT nombre, telefono, direccion, correo FROM usuarios WHERE id = ?";
        $stmt = mysqli_prepare($cnx, $sql_select);
        mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $nombre, $telefono, $direccion, $correo);
        mysqli_stmt_fetch($stmt);

        // Codificar los datos para prevenir XSS
        $nombre = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
        $telefono = htmlspecialchars($telefono, ENT_QUOTES, 'UTF-8');
        $direccion = htmlspecialchars($direccion, ENT_QUOTES, 'UTF-8');
        $correo = htmlspecialchars($correo, ENT_QUOTES, 'UTF-8');

        mysqli_stmt_close($stmt);
        desconectar();
    }
} catch (Exception $e) {
    // Capturar excepciones en Sentry y mostrar mensaje genérico al usuario
    \Sentry\captureException($e);
    echo "Ocurrió un error, por favor inténtelo más tarde.";
}
?>
