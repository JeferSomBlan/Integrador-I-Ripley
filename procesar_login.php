<?php
session_start();
include_once './util/conexionMysql.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
require './PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Conectar a la base de datos y procesar el login
    conectar();
    $identificacion = htmlspecialchars($_POST['identificacion']);
    $contrasena = htmlspecialchars($_POST['contrasena']);
    $clave = htmlspecialchars($_POST['clave']);

    $sql = "SELECT * FROM usuarios WHERE correo = '$identificacion' OR dni = '$identificacion'";
    $usuario = consultar($sql);

    if ($usuario && count($usuario) == 1) {
        $usuario = $usuario[0];

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
            echo "<script>alert('Contraseña o clave incorrecta'); window.location.href = 'login.php';</script>";
        }
    } else {
        echo "<script>alert('Usuario no encontrado'); window.location.href = 'login.php';</script>";
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
