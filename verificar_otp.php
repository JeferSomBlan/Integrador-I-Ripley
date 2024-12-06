<?php 
session_start();
include_once './util/conexionMysql.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
require './PHPMailer/src/Exception.php';

// Incluir Sentry
require_once './vendor/autoload.php';
\Sentry\init(['dsn' => 'https://50546abde49ec9c76f7562058fe9d492@o4508412475277312.ingest.us.sentry.io/4508417566638080']); 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['user_id'])) {
    \Sentry\captureMessage("Acceso no autorizado a verificar OTP", \Sentry\Severity::warning());
    header("Location: login.php");
    exit();
}

$errorMensaje = "";

// Verificar si se establecieron las variables de sesión necesarias
if (!isset($_SESSION['otp']) || !isset($_SESSION['otp_expiration'])) {
    $errorMensaje = "No se ha generado un código OTP o ha expirado. Por favor, inicia sesión de nuevo.";
    \Sentry\captureMessage("Error en OTP: Código OTP no generado o expirado.", \Sentry\Severity::error());
}

// Reenviar OTP
if (isset($_POST['reenviar_otp'])) {
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
        $mail->addAddress($_SESSION['correo']);
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Código de Verificación (OTP)';
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; color: #333;'>
                <h2 style='color: #007bff;'>¡Hola, {$_SESSION['nombre']}!</h2>
                <p>Se ha generado un nuevo código OTP para tu inicio de sesión en <strong>Ripley</strong>.</p>
                <p>Tu nuevo código de verificación es:</p>
                <p style='font-size: 24px; font-weight: bold; color: #007bff;'>$otp</p>
                <p style='font-size: 14px;'>Este código es válido por <strong>5 minutos</strong>.</p>
                <hr style='border: none; border-top: 1px solid #e0e0e0; margin-top: 20px;'>
                <p style='color: #999; font-size: 12px;'>Atentamente, <br> El equipo de Ripley</p>
            </div>
        ";

        $mail->send();
        \Sentry\captureMessage("OTP reenviado exitosamente al correo: {$_SESSION['correo']}", \Sentry\Severity::info());
        header("Location: verificar_otp.php?reenviado=1");
        exit();
    } catch (Exception $e) {
        $errorMensaje = "Error al reenviar el código OTP.";
        \Sentry\captureException($e);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['reenviar_otp'])) {
    $otpIngresado = intval($_POST['otp']);

    if ($otpIngresado === $_SESSION['otp'] && time() < $_SESSION['otp_expiration']) {
        unset($_SESSION['otp']);
        unset($_SESSION['otp_expiration']);
        \Sentry\captureMessage("Usuario {$_SESSION['user_id']} verificó OTP correctamente.", \Sentry\Severity::info());
        header("Location: ./intranet/intranet.php");
        exit();
    } else {
        $errorMensaje = "Código OTP incorrecto o expirado.";
        \Sentry\captureMessage("Error de verificación OTP para el usuario {$_SESSION['user_id']}. Código incorrecto o expirado.", \Sentry\Severity::warning());
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autentificación OTP</title>
    <link rel="icon" type="image/x-icon" href="./img/logo/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #eef2f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            max-width: 450px;
            background-color: #ffffff;
            border-radius: 16px;
            padding: 30px 40px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #007bff;
            font-size: 28px;
            font-weight: bold;
        }
        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 16px;
        }
        .form-control {
            font-size: 16px;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .btn-primary, .btn-secondary {
            border-radius: 12px;
            font-size: 16px;
            font-weight: bold;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-secondary:disabled {
            cursor: not-allowed;
            background-color: #b5b5b5;
            border-color: #b5b5b5;
        }
        .countdown-text {
            margin-top: 10px;
            text-align: center;
            color: #ff4b5c;
            font-size: 14px;
            font-weight: 500;
        }
        .error-message {
            margin-top: 15px;
            color: #ff4b5c;
            text-align: center;
            font-size: 15px;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('reenviado')) {
                iniciarCuentaRegresiva();
            }
        });

        function iniciarCuentaRegresiva() {
            const boton = document.getElementById('reenviar-btn');
            const countdownElement = document.getElementById('countdown');
            let tiempo = 15;

            boton.disabled = true;
            countdownElement.textContent = `Reintentar en ${tiempo} segundos`;

            const intervalo = setInterval(() => {
                tiempo--;
                if (tiempo > 0) {
                    countdownElement.textContent = `Reintentar en ${tiempo} segundos`;
                } else {
                    clearInterval(intervalo);
                    countdownElement.textContent = '';
                    boton.disabled = false;
                }
            }, 1000);
        }
    </script>
</head>
<body>
    <div class="container text-center">
        <h2><i class="fas fa-key"></i> Verificación de Código Ncrypt</h2>
        <form id="otp-form" method="POST">
            <div class="mb-3">
                <label for="otp" class="form-label">Ingresa el código Ncrypt enviado a tu correo:</label>
                <input type="text" id="otp" name="otp" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-3">Verificar</button>
        </form>
        <form method="POST">
            <button type="submit" id="reenviar-btn" name="reenviar_otp" class="btn btn-secondary w-100">Reenviar Código</button>
            <div id="countdown" class="countdown-text"></div>
        </form>
        <?php if ($errorMensaje): ?>
            <div class="error-message"><?php echo $errorMensaje; ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
