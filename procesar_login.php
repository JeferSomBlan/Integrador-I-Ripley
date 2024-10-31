<?php
include_once './util/conexionMysql.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar reCAPTCHA
    $recaptchaResponse = $_POST['g-recaptcha-response'];
    $secretKey = 'YOUR_SECRET_KEY'; // Reemplaza 'YOUR_SECRET_KEY' con la clave secreta de tu reCAPTCHA
    $verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptchaResponse");
    $responseData = json_decode($verifyResponse);

    if (!$responseData->success) {
        // Si reCAPTCHA falla, redirigir al login con un mensaje de error
        echo "<script>alert('Por favor, verifica el reCAPTCHA.'); window.location.href = 'login.php';</script>";
        exit();
    }

    // Conectar a la base de datos y procesar el login
    conectar();

    $identificacion = htmlspecialchars($_POST['identificacion']);
    $contrasena = htmlspecialchars($_POST['contrasena']);
    $clave = htmlspecialchars($_POST['clave']);

    // Consultar usuario por correo o DNI
    $sql = "SELECT * FROM usuarios WHERE correo = '$identificacion' OR dni = '$identificacion'";
    $usuario = consultar($sql);

    if ($usuario && count($usuario) == 1) {
        $usuario = $usuario[0];

        // Verificar contraseña y clave
        if (password_verify($contrasena, $usuario['contrasena']) && password_verify($clave, $usuario['clave'])) {
            session_start();
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            header('Location: ./intranet/intranet.php');
        } else {
            echo "<script>alert('Contraseña o clave incorrecta'); window.location.href = 'login.php';</script>";
        }
    } else {
        echo "<script>alert('Usuario no encontrado'); window.location.href = 'login.php';</script>";
    }

    desconectar();
}
?>
