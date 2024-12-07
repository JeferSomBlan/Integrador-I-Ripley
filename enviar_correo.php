<?php
require 'util/conexionMysql.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
require './PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Conectar a la base de datos
conectar();

// Configurar PHPMailer
function configurarMailer() {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'jefersoncrack21@gmail.com'; // Cambia por tu correo
    $mail->Password = 'lcybrrzzxygsxcpd'; // Cambia por tu contraseña
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->setFrom('jefersoncrack21@gmail.com', 'Tu Nombre o Empresa');
    $mail->isHTML(true); // Activar contenido HTML
    $mail->CharSet = 'UTF-8'; // Para manejar caracteres especiales
    return $mail;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Enviar correos personalizados
    if (isset($_POST['enviar_individual'])) {
        $usuarios = consultar("SELECT nombre, correo FROM usuarios");
        $errores = [];
        $enviados = 0;

        foreach ($usuarios as $usuario) {
            try {
                $mail = configurarMailer();
                $mail->addAddress($usuario['correo']);
                $mail->Subject = 'Correo Personalizado';
                $mail->Body = "
                    <h2>Hola, {$usuario['nombre']}!</h2>
                    <p>Este es un correo personalizado solo para ti.</p>
                    <p>Gracias por ser parte de nuestra comunidad.</p>
                ";
                $mail->send();
                $enviados++;
            } catch (Exception $e) {
                $errores[] = "Error al enviar a {$usuario['correo']}: " . $e->getMessage();
            }
        }

        echo "<h3>Correos enviados: $enviados</h3>";
        if (!empty($errores)) {
            echo "<h4>Errores:</h4><ul>";
            foreach ($errores as $error) {
                echo "<li>$error</li>";
            }
            echo "</ul>";
        }
    }

    // Enviar correos masivos
    if (isset($_POST['enviar_masivo'])) {
        $usuarios = consultar("SELECT correo FROM usuarios");
        $correos = array_column($usuarios, 'correo'); // Extraer solo los correos

        try {
            $mail = configurarMailer();
            foreach ($correos as $correo) {
                $mail->addBCC($correo); // Usamos BCC para enviar el mismo correo a todos
            }
            $mail->Subject = 'Correo Masivo';
            $mail->Body = "
                <h2>Hola a todos!</h2>
                <p>Este es un correo masivo para todos nuestros usuarios.</p>
                <p>Gracias por ser parte de nuestra comunidad.</p>
            ";
            $mail->send();
            echo "<h3>Correo masivo enviado a " . count($correos) . " usuarios</h3>";
        } catch (Exception $e) {
            echo "<h4>Error al enviar el correo masivo: {$mail->ErrorInfo}</h4>";
        }
    }
}

// Desconectar de la base de datos
desconectar();
