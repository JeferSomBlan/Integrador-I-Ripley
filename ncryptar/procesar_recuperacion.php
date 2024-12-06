<?php
session_start();
include_once '../util/conexionMysql.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Sentry\Severity;

// Inicializar Sentry
\Sentry\init(['dsn' => 'https://50546abde49ec9c76f7562058fe9d492@o4508412475277312.ingest.us.sentry.io/4508417566638080']);

$response = ["success" => false, "message" => ""];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validar el correo electrónico enviado
        if (empty($_POST['correo']) || !filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Correo inválido o no enviado.");
        }

        $correo = htmlspecialchars($_POST['correo'], ENT_QUOTES, 'UTF-8');

        conectar();

        // Consulta preparada para evitar inyecciones SQL
        $sql = "SELECT * FROM usuarios WHERE correo = ?";
        if ($stmt = mysqli_prepare($cnx, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $correo);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $usuario = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);

            if ($usuario) {
                $token = bin2hex(random_bytes(32));
                $tokenExpiracion = date("Y-m-d H:i:s", strtotime('+1 hour'));

                // Actualizar el token en la base de datos
                $sql_update = "UPDATE usuarios SET token_recuperacion = ?, token_expiracion = ? WHERE correo = ?";
                if ($stmt = mysqli_prepare($cnx, $sql_update)) {
                    mysqli_stmt_bind_param($stmt, "sss", $token, $tokenExpiracion, $correo);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                } else {
                    throw new Exception("Error al actualizar el token en la base de datos.");
                }

                // Enviar correo de recuperación
                $mail = new PHPMailer(true);

                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'jefersoncrack21@gmail.com'; // Cambia esto
                    $mail->Password = 'lcybrrzzxygsxcpd';      // Cambia esto
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    $mail->setFrom('jefersoncrack21@gmail.com', 'Ripley');
                    $mail->addAddress($correo);
                    $mail->isHTML(true);
                    $mail->CharSet = 'UTF-8';
                    $mail->Subject = 'Recuperación de Contraseña - Ripley';

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
                            <p>Este enlace estará disponible por <strong>1 hora</strong>. Si no solicitaste este cambio, puedes ignorar este mensaje.</p>
                        </div>
                    ";

                    $mail->send();

                    // Registro exitoso en Sentry
                    \Sentry\captureMessage("Correo de recuperación enviado al usuario ID: {$usuario['id']} con correo: $correo", Severity::info());

                    $response["success"] = true;
                    $response["message"] = "Correo de recuperación enviado.";
                } catch (Exception $e) {
                    \Sentry\captureException($e);
                    throw new Exception("Error al enviar el correo: {$mail->ErrorInfo}");
                }
            } else {
                // Usuario no encontrado
                \Sentry\captureMessage("Intento de recuperación para correo no registrado: $correo", Severity::warning());
                $response["message"] = "No se encontró un usuario con ese correo.";
            }
        } else {
            throw new Exception("Error al preparar la consulta para buscar el usuario.");
        }

        desconectar();
    }
} catch (Exception $e) {
    // Registrar excepciones en Sentry
    \Sentry\captureException($e);
    $response["message"] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>