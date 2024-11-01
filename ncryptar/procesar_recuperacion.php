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
            $mail->Subject = 'Recuperación de Contraseña - Ripley';

            $urlRecuperacion = "http://localhost/ripley/ncryptar/restablecer_contrasena.php?token=$token";

            $mail->Body = "<p>Hola, {$usuario['nombre']}.</p><p>Haz clic en el siguiente enlace para restablecer tu contraseña:</p><p><a href='$urlRecuperacion'>Restablecer Contraseña</a></p><p>Este enlace es válido por 1 hora.</p>";
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
