<?php
include_once './util/conexionMysql.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
