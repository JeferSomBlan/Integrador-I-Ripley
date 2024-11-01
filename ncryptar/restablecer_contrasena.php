<?php
session_start();
require '../util/conexionMysql.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $nueva_contrasena = password_hash($_POST['nueva_contrasena'], PASSWORD_BCRYPT);

    conectar();
    // Verificar si el token es válido
    $sql = "SELECT * FROM usuarios WHERE token_recuperacion = '$token'";
    $resultado = consultar($sql);

    if (count($resultado) === 1) {
        $user_id = $resultado[0]['id'];
        
        // Actualizar la contraseña y eliminar el token
        $sql_update = "UPDATE usuarios SET contrasena = '$nueva_contrasena', token_recuperacion = NULL WHERE id = $user_id";
        ejecutar($sql_update);

        echo "<script>alert('Contraseña actualizada correctamente.'); window.location.href = 'login.php';</script>";
    } else {
        echo "<script>alert('Token inválido o expirado.'); window.location.href = 'recuperar_contrasena.php';</script>";
    }

    desconectar();
} else {
    // Mostrar formulario si el método es GET
    $token = $_GET['token'] ?? '';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña - Ripley</title>
</head>
<body>
    <form action="restablecer_contrasena.php" method="POST">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        <label>Nueva Contraseña:</label>
        <input type="password" name="nueva_contrasena" required>
        <button type="submit">Actualizar Contraseña</button>
    </form>
</body>
</html>
