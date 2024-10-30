<?php
session_start();
include_once '../util/conexionMysql.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $nombre = htmlspecialchars($_POST['nombre']);
    $telefono = htmlspecialchars($_POST['telefono']);
    $direccion = htmlspecialchars($_POST['direccion']);
    $correo = htmlspecialchars($_POST['email']);

    // Conectar y actualizar la base de datos
    conectar();
    $sql_update = "UPDATE usuarios SET nombre = '$nombre', telefono = '$telefono', direccion = '$direccion', correo = '$correo' WHERE id = $user_id";
    $actualizado = ejecutar($sql_update);
    desconectar();

    // Redirigir de vuelta a mi_cuenta.php con mensaje de éxito
    header("Location: mi_cuenta.php?actualizado=1");
    exit();
}
?>
