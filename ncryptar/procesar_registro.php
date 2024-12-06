<?php
include_once '../util/conexionMysql.php'; // Conexión a la base de datos

// Incluir el autoload de Composer para Sentry
require_once '../vendor/autoload.php';

// Iniciar Sentry
\Sentry\init(['dsn' => 'https://50546abde49ec9c76f7562058fe9d492@o4508412475277312.ingest.us.sentry.io/4508417566638080']);

use Sentry\Severity;

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        conectar();

        // Validar los campos enviados
        $campos_obligatorios = ['nombre', 'correo', 'dni', 'direccion', 'telefono', 'contrasena'];
        foreach ($campos_obligatorios as $campo) {
            if (empty($_POST[$campo])) {
                throw new Exception("El campo {$campo} es obligatorio.");
            }
        }

        // Validar formato de DNI (8 números)
        if (!preg_match('/^\d{8}$/', $_POST['dni'])) {
            throw new Exception("El DNI debe contener exactamente 8 números.");
        }

        // Validar formato de teléfono (9 números)
        if (!preg_match('/^\d{9}$/', $_POST['telefono'])) {
            throw new Exception("El número de teléfono debe contener exactamente 9 números.");
        }

        $nombre = htmlspecialchars($_POST['nombre']);
        $correo = htmlspecialchars($_POST['correo']);
        $dni = htmlspecialchars($_POST['dni']);
        $direccion = htmlspecialchars($_POST['direccion']);
        $telefono = htmlspecialchars($_POST['telefono']);
        $contrasena = htmlspecialchars($_POST['contrasena']);

        $sql_verificar = "SELECT * FROM usuarios WHERE correo = '$correo' OR dni = '$dni'";
        $usuarios_existentes = consultar($sql_verificar);

        if (count($usuarios_existentes) > 0) {
            // Registro fallido: Correo o DNI ya están registrados
            $mensaje_error = 'El correo o DNI ya están registrados';
            \Sentry\captureMessage($mensaje_error, Severity::warning()); // Registrar como advertencia
            echo json_encode(['success' => false, 'message' => $mensaje_error]);
        } else {
            $contrasenaHash = password_hash($contrasena, PASSWORD_BCRYPT);
            $clave = bin2hex(random_bytes(16));
            $claveHash = password_hash($clave, PASSWORD_BCRYPT);

            $sql_insertar = "INSERT INTO usuarios (nombre, correo, dni, direccion, telefono, contrasena, clave) 
                            VALUES ('$nombre', '$correo', '$dni', '$direccion', '$telefono', '$contrasenaHash', '$claveHash')";

            if (ejecutar($sql_insertar)) {
                // Registro exitoso
                \Sentry\captureMessage("Nuevo registro exitoso: $correo", Severity::info()); // Registrar como información
                echo json_encode(['success' => true, 'clave' => $clave]);
            } else {
                // Error al insertar en la base de datos
                throw new Exception('Error en el registro');
            }
        }

        desconectar();
    }
} catch (Exception $e) {
    // Capturar cualquier excepción no manejada
    \Sentry\captureException($e); // Registrar la excepción en Sentry
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
