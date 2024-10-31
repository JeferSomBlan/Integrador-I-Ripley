<?php
session_start();
include_once './util/conexionMysql.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar CAPTCHA usando el valor en el campo oculto del formulario
    $captchaIngresado = intval($_POST['captcha']); // valor enviado desde el campo oculto
    
    // Compara el valor del campo oculto con el CAPTCHA generado y almacenado en la sesión
    if ($captchaIngresado !== $_SESSION['captcha_result']) {
        // Si el CAPTCHA falla, redirigir al login con un mensaje de error
        echo "<script>alert('CAPTCHA incorrecto. Inténtalo de nuevo.'); window.location.href = 'login.php';</script>";
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
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            header('Location: ./intranet/intranet.php');
            exit();
        } else {
            echo "<script>alert('Contraseña o clave incorrecta'); window.location.href = 'login.php';</script>";
        }
    } else {
        echo "<script>alert('Usuario no encontrado'); window.location.href = 'login.php';</script>";
    }

    desconectar();
}
