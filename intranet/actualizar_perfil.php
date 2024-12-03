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
    $nombre = trim(htmlspecialchars($_POST['nombre']));
    $telefono_raw = trim(htmlspecialchars($_POST['telefono'])); // Obtener el teléfono sin cambios
    $telefono = preg_replace('/\D/', '', $telefono_raw); // Eliminar cualquier carácter no numérico
    $direccion = trim(htmlspecialchars($_POST['direccion']));
    $correo = trim(htmlspecialchars($_POST['email']));

    // Debug: Mostrar el valor del teléfono antes de la validación
    echo "Telefono original: " . $telefono_raw . "<br>"; // Ver qué valor recibe
    echo "Telefono procesado: " . $telefono . "<br>"; // Ver qué valor está procesando
    echo "Tipo de telefono procesado: " . gettype($telefono) . "<br>"; // Verificar tipo de variable

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
?>
