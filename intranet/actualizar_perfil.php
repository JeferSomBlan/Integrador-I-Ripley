<?php
session_start();
include_once '../util/conexionMysql.php';

// Verificar que el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el ID de usuario de la sesión
    $user_id = $_SESSION['user_id'];
    
    // Limpiar y sanitizar las entradas
    $nombre = trim(htmlspecialchars($_POST['nombre'], ENT_QUOTES, 'UTF-8')); // Codificar las entradas para prevenir XSS
    $telefono_raw = trim(htmlspecialchars($_POST['telefono'], ENT_QUOTES, 'UTF-8')); // Sanitizar teléfono
    $telefono = preg_replace('/\D/', '', $telefono_raw); // Eliminar cualquier carácter no numérico
    $direccion = trim(htmlspecialchars($_POST['direccion'], ENT_QUOTES, 'UTF-8')); // Codificar dirección
    $correo = trim(htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8')); // Codificar correo

    // Validación simple de formato de correo
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo "El correo electrónico no es válido.";
        exit();
    }

    // Verificación de que el teléfono sea numérico y tenga entre 9 y 15 dígitos
    if (!is_numeric($telefono) || strlen($telefono) < 9 || strlen($telefono) > 15) {
        echo "El número de teléfono no es válido. Asegúrate de que solo contiene números y tiene entre 9 y 15 dígitos.";
        exit();
    }

    // Validación de dirección (mínimo 5 caracteres)
    if (strlen($direccion) < 5) {
        echo "La dirección es demasiado corta.";
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

    // Desconectar de la base de datos
    desconectar();

    exit();
}

// Si la petición es GET (para mostrar el formulario con los datos actuales del usuario)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Conectar a la base de datos
    conectar();

    // Consulta para obtener los datos del usuario
    $sql_select = "SELECT nombre, telefono, direccion, correo FROM usuarios WHERE id = ?";
    if ($stmt = mysqli_prepare($cnx, $sql_select)) {
        mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $nombre, $telefono, $direccion, $correo);
        mysqli_stmt_fetch($stmt);

        // Codificar los datos para prevenir XSS al mostrarlos en el formulario
        $nombre = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
        $telefono = htmlspecialchars($telefono, ENT_QUOTES, 'UTF-8');
        $direccion = htmlspecialchars($direccion, ENT_QUOTES, 'UTF-8');
        $correo = htmlspecialchars($correo, ENT_QUOTES, 'UTF-8');

        mysqli_stmt_close($stmt);
    } else {
        echo "Error al obtener los datos del usuario.";
    }

    // Desconectar de la base de datos
    desconectar();
}
?>
