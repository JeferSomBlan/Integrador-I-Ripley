<?php
session_start();
require '../util/conexionMysql.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $nueva_contrasena = password_hash($_POST['nueva_contrasena'], PASSWORD_BCRYPT);

    conectar();
    $sql = "SELECT * FROM usuarios WHERE token_recuperacion = '$token' AND token_expiracion > NOW()";
    $resultado = consultar($sql);

    if (count($resultado) === 1) {
        $user_id = $resultado[0]['id'];
        
        // Actualizar la contraseña y eliminar el token
        $sql_update = "UPDATE usuarios SET contrasena = '$nueva_contrasena', token_recuperacion = NULL, token_expiracion = NULL WHERE id = $user_id";
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css'>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        header {
            background-color: #343a40;
            padding: 15px 0;
        }

        .navbar-light .navbar-nav .nav-link {
            color: #f8f9fa;
        }

        .jumbotron {
            background-color: #495057;
            color: #f8f9fa;
            padding: 40px;
            border-radius: 10px;
            margin-bottom: 40px;
        }

        .footer {
            background-color: #343a40;
            padding: 20px 0;
            color: #f8f9fa;
        }

        .form-label {
            text-align: left;
            margin-bottom: 10px;
            margin-top: 15px;
        }

        .btn-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .form-section {
            margin-top: 40px;
            margin-bottom: 40px;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <?php require_once '../fragmentos/nc_header.php'; ?>

    <main class="container mt-4 form-section">
        <!-- Contenedor para Título y Descripción -->
        <section class="jumbotron text-center mb-4">
            <h1 class="display-4">Restablecer Contraseña</h1>
            <p class="lead">Ingrese una nueva contraseña para su cuenta.</p>
        </section>

        <!-- Formulario de Restablecimiento de Contraseña -->
        <form action="restablecer_contrasena.php" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <div class="mb-3">
                <label for="nueva_contrasena" class="form-label">Nueva Contraseña:</label>
                <input type="password" name="nueva_contrasena" id="nueva_contrasena" class="form-control" required placeholder="Ingrese su nueva contraseña">
            </div>
            <div class="btn-container">
                <button type="submit" class="btn btn-primary">Actualizar Contraseña</button>
            </div>
        </form>
    </main>
    
    <?php require_once '../fragmentos/nc_footer.php'; ?>

    <!-- Scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>