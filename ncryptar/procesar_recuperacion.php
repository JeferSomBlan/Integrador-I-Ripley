<?php
session_start();
include_once '../util/conexionMysql.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el correo desde el formulario
    $correo = htmlspecialchars($_POST['correo']);

    // Conectar a la base de datos y verificar si el correo existe
    conectar();
    $sql = "SELECT * FROM usuarios WHERE correo = '$correo'";
    $resultado = consultar($sql);

    if ($resultado && count($resultado) === 1) {
        $usuario = $resultado[0];
        $nuevoPassword = bin2hex(random_bytes(4)); // Genera una nueva contraseña aleatoria
        $nuevoPasswordEncriptado = password_hash($nuevoPassword, PASSWORD_DEFAULT); // Encripta la contraseña

        // Actualizar la contraseña en la base de datos
        $updateSql = "UPDATE usuarios SET contrasena = '$nuevoPasswordEncriptado' WHERE correo = '$correo'";
        ejecutar($updateSql);

        // Configuración de PHPMailer para enviar el correo de recuperación
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Asegúrate de usar el host correcto
            $mail->SMTPAuth = true;
            $mail->Username = 'jefersoncrack21@gmail.com'; // Reemplaza con tu correo
            $mail->Password = 'lcybrrzzxygsxcpd'; // Reemplaza con tu contraseña de aplicación
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Configuración del correo
            $mail->setFrom('jefersoncrack21@gmail.com', 'Ripley');
            $mail->addAddress($correo);
            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de Contraseña - Ripley';
            $mail->Body = "<p>Hola, {$usuario['nombre']}.</p><p>Tu nueva contraseña es: <strong>{$nuevoPassword}</strong></p><p>Te recomendamos cambiar esta contraseña después de iniciar sesión.</p>";

            $mail->send();
            echo "<script>alert('Se ha enviado un correo con la nueva contraseña.'); window.location.href = '../login.php';</script>";
        } catch (Exception $e) {
            echo "<script>alert('Hubo un error al enviar el correo: {$mail->ErrorInfo}'); window.location.href = 'recuperar_contrasena.php';</script>";
        }
    } else {
        echo "<script>alert('No se encontró un usuario con ese correo.'); window.location.href = 'recuperar_contrasena.php';</script>";
    }

    desconectar();
}
?>
