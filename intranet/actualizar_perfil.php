<?php
session_start();
include_once '../util/conexionMysql.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario y limpiarlos
    $user_id = $_SESSION['user_id'];
    $nombre = trim(htmlspecialchars($_POST['nombre']));
    $telefono = trim(htmlspecialchars($_POST['telefono']));
    $direccion = trim(htmlspecialchars($_POST['direccion']));
    $correo = trim(htmlspecialchars($_POST['email']));

    // Validación simple (por ejemplo, formato de correo)
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo "El correo electrónico no es válido.";
        exit();
    }
    
    // Conectar a la base de datos
    conectar();

    // Consulta preparada para actualizar el perfil
    $sql_update = "UPDATE usuarios SET nombre = ?, telefono = ?, direccion = ?, correo = ? WHERE id = ?";
    
    // Preparar la consulta
    if ($stmt = mysqli_prepare($cnx, $sql_update)) {
        
        // Vincular los parámetros
        mysqli_stmt_bind_param($stmt, "ssssi", $nombre, $telefono, $direccion, $correo, $user_id);

        // Ejecutar la consulta
        if (mysqli_stmt_execute($stmt)) {
            // Redirigir con mensaje de éxito
            header("Location: mi_cuenta.php?actualizado=1");
        } else {
            // Si la actualización falla
            echo "Error al actualizar el perfil. Intenta nuevamente.";
        }

        // Cerrar la declaración
        mysqli_stmt_close($stmt);
    } else {
        // Si la preparación de la consulta falla
        echo "Error al preparar la consulta.";
    }

    // Desconectar
    desconectar();

    exit();
}
?>
