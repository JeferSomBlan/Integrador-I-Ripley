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
    $mail->setFrom('jefersoncrack21@gmail.com', 'Ripley - Facturación');
    $mail->isHTML(true); // Activar contenido HTML
    $mail->CharSet = 'UTF-8'; // Para manejar caracteres especiales
    return $mail;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuarios = consultar("SELECT nombre, correo, direccion, telefono, dni FROM usuarios");

    // Enviar boletas personalizadas
    if (isset($_POST['enviar_individual'])) {
        $errores = [];
        $enviados = 0;

        foreach ($usuarios as $usuario) {
            try {
                $mail = configurarMailer();

                // Generar un número de boleta aleatorio
                $numeroBoleta = rand(100000, 999999);

                $mail->addAddress($usuario['correo']);
                $mail->Subject = "Boleta Electrónica N° $numeroBoleta - Ripley";
                $mail->Body = "
                    <div style='font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 10px;'>
                        <div style='text-align: center; margin-bottom: 20px;'>
                            <img src='cid:logoRipley' alt='Ripley' style='width: 150px; height: auto;'>
                        </div>
                        <h1 style='text-align: center; color: #333;'>Boleta Electrónica</h1>
                        <h3 style='text-align: center; color: #777;'>N° $numeroBoleta</h3>
                        <hr style='border: 0; border-top: 1px solid #ddd; margin: 20px 0;'>
                        <p style='text-align: center; font-size: 16px; color: #555;'>
                            Estimado(a) <strong>{$usuario['nombre']}</strong>,<br>
                            Gracias por confiar en nosotros. A continuación, te presentamos los detalles de tu compra:
                        </p>
                        <table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>
                            <tr>
                                <th style='text-align: left; padding: 8px; background-color: #f2f2f2;'>DNI:</th>
                                <td style='padding: 8px;'>{$usuario['dni']}</td>
                            </tr>
                            <tr>
                                <th style='text-align: left; padding: 8px; background-color: #f2f2f2;'>Dirección:</th>
                                <td style='padding: 8px;'>{$usuario['direccion']}</td>
                            </tr>
                            <tr>
                                <th style='text-align: left; padding: 8px; background-color: #f2f2f2;'>Teléfono:</th>
                                <td style='padding: 8px;'>{$usuario['telefono']}</td>
                            </tr>
                        </table>
                        <p style='text-align: center; font-size: 16px; color: #555;'>
                            Puedes descargar tu boleta y verificar tus transacciones accediendo a nuestra plataforma en línea.
                        </p>
                        <div style='text-align: center; margin-top: 20px;'>
                            <a href='https://ripley.com/mis-compras' 
                                style='background-color: #ff4500; color: white; text-decoration: none; padding: 10px 20px; border-radius: 5px; font-size: 16px;'>
                                Ver Mis Compras
                            </a>
                        </div>
                        <p style='text-align: center; margin-top: 20px; font-size: 14px; color: #777;'>
                            Si tienes alguna consulta, no dudes en contactarnos.<br>
                            Equipo Ripley.
                        </p>
                    </div>
                ";

                // Adjuntar logo de Ripley como recurso embebido
                $mail->addEmbeddedImage('img/logo/ripley_logo.png', 'logoRipley');

                $mail->send();
                $enviados++;
            } catch (Exception $e) {
                $errores[] = "Error al enviar a {$usuario['correo']}: " . $e->getMessage();
            }
        }

        echo "<h3>Boletas enviadas: $enviados</h3>";
        if (!empty($errores)) {
            echo "<h4>Errores:</h4><ul>";
            foreach ($errores as $error) {
                echo "<li>$error</li>";
            }
            echo "</ul>";
        }
    }

    // Enviar correo masivo
    if (isset($_POST['enviar_masivo'])) {
        try {
            $mail = configurarMailer();
            $mail->Subject = "¡Grandes Ofertas en Ripley!";
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;'>
                    <h1 style='text-align: center; color: #333;'>¡No te pierdas nuestras ofertas!</h1>
                    <p style='text-align: center; font-size: 16px; color: #555;'>
                        Visita nuestra página y descubre promociones increíbles en tus productos favoritos.
                    </p>
                    <div style='text-align: center; margin-top: 20px;'>
                        <a href='https://ripley.com/ofertas' 
                            style='background-color: #ff4500; color: white; text-decoration: none; padding: 10px 20px; border-radius: 5px; font-size: 16px;'>
                            Ir a Ofertas
                        </a>
                    </div>
                </div>
            ";

            foreach ($usuarios as $usuario) {
                $mail->addAddress($usuario['correo']);
            }

            $mail->send();
            echo "<h3>Correo masivo enviado a todos los usuarios registrados.</h3>";
        } catch (Exception $e) {
            echo "<h4>Error al enviar correo masivo: {$e->getMessage()}</h4>";
        }
    }
}

// Desconectar de la base de datos
desconectar();
