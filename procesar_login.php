<?php 
session_start();
include_once './util/conexionMysql.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
require './PHPMailer/src/Exception.php';

// Al inicio de tu archivo, configura el logger
require_once 'vendor/autoload.php';
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log = new Logger('login_log');
$logDir = __DIR__ . '/logs';
if (!file_exists($logDir)) {
    mkdir($logDir, 0777, true);
}
$log->pushHandler(new StreamHandler($logDir . '/app.log', Logger::WARNING));

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Verificar si la solicitud es POST y si el token CSRF es válido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar el token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $log->error('Error: CSRF token inválido', ['ip' => $_SERVER['REMOTE_ADDR'], 'user_agent' => $_SERVER['HTTP_USER_AGENT']]);
        die('Error: CSRF token inválido');
    }

    // Conectar a la base de datos y procesar el login
    conectar();

    // Escapar entradas para evitar XSS
    $identificacion = htmlspecialchars($_POST['identificacion']);
    $contrasena = $_POST['contrasena'];  // Contraseña ingresada por el usuario
    $clave = $_POST['clave'];  // Clave adicional si es necesario

    // Evitar inyecciones SQL usando consultas preparadas
    $sql = "SELECT * FROM usuarios WHERE correo = ? OR dni = ?";
    if ($stmt = mysqli_prepare($cnx, $sql)) {
        // Vincular los parámetros para prevenir inyecciones SQL
        mysqli_stmt_bind_param($stmt, "ss", $identificacion, $identificacion);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($usuario = mysqli_fetch_assoc($result)) {
            // Verificar las contraseñas usando password_verify()
            if (password_verify($contrasena, $usuario['contrasena']) && password_verify($clave, $usuario['clave'])) {
                // Almacenar información relevante en la sesión
                $_SESSION['user_id'] = $usuario['id'];
                $_SESSION['nombre'] = $usuario['nombre'];
                $_SESSION['correo'] = $usuario['correo']; // Para reenviar OTP en verificar_otp.php

                // Generar y enviar OTP
                generarYEnviarOTP($usuario['correo'], $usuario['nombre']);
                header('Location: verificar_otp.php');
                exit();
            } else {
                // Log de error cuando las credenciales son incorrectas
                $log->warning('Credenciales incorrectas', [
                    'ip' => $_SERVER['REMOTE_ADDR'], 
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                    'identificacion' => $identificacion
                ]);
                echo "<script>alert('Contraseña o clave incorrecta'); window.location.href = 'login.php';</script>";
            }
        } else {
            // Log de error cuando el usuario no es encontrado
            $log->warning('Usuario no encontrado', [
                'ip' => $_SERVER['REMOTE_ADDR'], 
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'identificacion' => $identificacion
            ]);
            echo "<script>alert('Usuario no encontrado'); window.location.href = 'login.php';</script>";
        }

        mysqli_stmt_close($stmt);
    } else {
        // Log de error cuando la consulta falla
        $log->error('Error en la consulta a la base de datos', [
            'ip' => $_SERVER['REMOTE_ADDR'], 
            'user_agent' => $_SERVER['HTTP_USER_AGENT']
        ]);
        echo "<script>alert('Error en la consulta a la base de datos'); window.location.href = 'login.php';</script>";
    }

    desconectar();
}

/**
 * Función para generar y enviar un código OTP al correo electrónico.
 */
function generarYEnviarOTP($correo, $nombre) {
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_expiration'] = time() + 300; // 5 minutos de validez

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'jefersoncrack21@gmail.com';
        $mail->Password = 'lcybrrzzxygsxcpd';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('jefersoncrack21@gmail.com', 'Ripley');
        $mail->addAddress($correo);
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8'; // Configura UTF-8 para el contenido del correo
        $mail->Subject = 'Código de Verificación (Ncrypt)';
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; color: #333;'>
                <h2 style='color: #007bff;'>¡Hola, {$nombre}!</h2>
                <p>Se ha solicitado un inicio de sesión en tu cuenta de <strong>Ripley</strong>.</p>
                <p>Para completar tu acceso, por favor utiliza el siguiente código de verificación (Ncrypt):</p>
                <p style='font-size: 24px; font-weight: bold; color: #007bff;'>$otp</p>
                <p style='font-size: 14px;'>Este código es válido por <strong>5 minutos</strong>. Si no fuiste tú quien intentó iniciar sesión, por favor ignora este correo y contacta a nuestro equipo de soporte.</p>
                <hr style='border: none; border-top: 1px solid #e0e0e0; margin-top: 20px;'>
                <p style='color: #999; font-size: 12px;'>Atentamente, <br> El equipo de Ripley</p>
            </div>
        ";

        $mail->send();
    } catch (Exception $e) {
        echo "<script>alert('Error al enviar el correo: {$mail->ErrorInfo}'); window.location.href = 'login.php';</script>";
        exit();
    }
}