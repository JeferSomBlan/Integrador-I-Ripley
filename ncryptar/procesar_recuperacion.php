<?php
session_start();
include_once '../util/conexionMysql.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$response = ["success" => false, "message" => ""];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = htmlspecialchars($_POST['correo']);

    conectar();
    $sql = "SELECT * FROM usuarios WHERE correo = '$correo'";
    $resultado = consultar($sql);

    if ($resultado && count($resultado) === 1) {
        $usuario = $resultado[0];
        
        $token = bin2hex(random_bytes(32));
        $tokenExpiracion = date("Y-m-d H:i:s", strtotime('+1 hour'));

        $sql_update = "UPDATE usuarios SET token_recuperacion = '$token', token_expiracion = '$tokenExpiracion' WHERE correo = '$correo'";
        ejecutar($sql_update);

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
            $mail->Subject = 'Recuperación de Contraseña - Ripley'; // Ajusta el asunto con acentos y caracteres especiales

            $urlRecuperacion = "http://localhost/ripley/ncryptar/restablecer_contrasena.php?token=$token";

            $mail->Body = "
                <div style='font-family: Arial, sans-serif; color: #333;'>
                    <h2 style='color: #4CAF50;'>Hola, {$usuario['nombre']}!</h2>
                    <p>Recibimos una solicitud para restablecer tu contraseña en <strong>Ripley</strong>.</p>
                    <p>Para continuar con el proceso, haz clic en el siguiente botón:</p>
                    <p>
                        <a href='$urlRecuperacion' 
                            style='display: inline-block; background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-size: 16px;'>
                            Restablecer Contraseña
                        </a>
                    </p>
                    <p>Este enlace estará disponible por <strong>1 hora</strong>. Si no solicitaste este cambio, puedes ignorar este mensaje y tu contraseña actual permanecerá segura.</p>
                    <p>¿Tienes alguna duda o necesitas ayuda? Contáctanos en nuestro equipo de soporte: 
                        <a href='mailto:soporte@ripley.com' style='color: #4CAF50;'>soporte@ripley.com</a>
                    </p>
                    <p style='color: #999; font-size: 14px;'>Atentamente, <br> El equipo de Ripley</p>
                    <hr style='border: none; border-top: 1px solid #e0e0e0;'>
                    <p style='color: #999; font-size: 12px;'>
                        Si no solicitaste el cambio de contraseña, por favor ignora este mensaje. Este enlace expirará automáticamente después de 1 hora.
                    </p>
                </div>
            ";
            $mail->send();

            $response["success"] = true;
            $response["message"] = "Correo de recuperación enviado";
        } catch (Exception $e) {
            $response["message"] = "Error al enviar el correo: {$mail->ErrorInfo}";
        }
    } else {
        $response["message"] = "No se encontró un usuario con ese correo.";
    }

    desconectar();
}

header('Content-Type: application/json');
echo json_encode($response);
?>
