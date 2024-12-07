<?php
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;

// Ruta del archivo de log
$logFile = __DIR__ . '/logs/conexion.log'; // Ajustado para apuntar correctamente al archivo en "util/logs/"
$recipient = 'jefersoncrack21@gmail.com'; // Cambia por tu correo personal o corporativo

// Verificar si el archivo de log existe
if (file_exists($logFile)) {
    $errors = [];
    $lines = file($logFile); // Leer el archivo línea por línea

    // Buscar líneas que contengan la palabra "ERROR"
    foreach ($lines as $line) {
        if (strpos($line, 'ERROR') !== false) {
            $errors[] = $line;
        }
    }

    // Si hay errores, enviarlos por correo
    if (!empty($errors)) {
        $mail = new PHPMailer(true); // Activar excepciones para errores
        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Servidor SMTP de Gmail
            $mail->SMTPAuth = true;
            $mail->Username = 'jefersoncrack21@gmail.com'; // Tu correo
            $mail->Password = 'lcybrrzzxygsxcpd'; // Contraseña de aplicación de Gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Configuración del correo
            $mail->setFrom('jefersoncrack21@gmail.com', 'Mantenimiento Ripley');
            $mail->addAddress($recipient);
            $mail->isHTML(false); // Enviar en texto plano
            $mail->CharSet = 'UTF-8'; // Para soportar caracteres especiales
            $mail->Subject = 'Errores críticos detectados en el sistema';
            $mail->Body = "Se han detectado los siguientes errores:\n\n" . implode("\n", $errors);

            // Enviar el correo
            $mail->send();
            echo "Correo de notificación enviado correctamente.\n";
        } catch (Exception $e) {
            // Manejo de errores al enviar el correo
            echo "Error al enviar el correo: " . $e->getMessage() . "\n";
        }
    } else {
        echo "No se encontraron errores críticos en los logs.\n";
    }
} else {
    echo "Archivo de log no encontrado: $logFile\n";
}
