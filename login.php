<?php
session_start();

// Incluir autoload de Composer para Monolog
require_once 'vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Crear el logger
$log = new Logger('login_log');

// Asegurarse de que el directorio de logs exista
$logDir = __DIR__ . '/logs';
if (!file_exists($logDir)) {
    mkdir($logDir, 0777, true); // Crear la carpeta si no existe
}

// Configurar el handler para escribir los logs de acceso
$log->pushHandler(new StreamHandler($logDir . '/app.log', Logger::INFO));

// Generar un token CSRF si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Genera un token único
}

// Registrar el acceso a la página de login
$log->info('Acceso a la página de login', ['ip' => $_SERVER['REMOTE_ADDR'], 'user_agent' => $_SERVER['HTTP_USER_AGENT']]);

$error = null;
if (isset($_GET["e"])) {
    $error = "Credenciales incorrectas";
    // Log de error en el login
    $log->warning('Intento de login fallido', [
        'ip' => $_SERVER['REMOTE_ADDR'], 
        'user_agent' => $_SERVER['HTTP_USER_AGENT'],
        'error_message' => $error
    ]);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - Ripley</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/login.css">
    <link rel="icon" type="image/x-icon" href="./img/logo/favicon.ico">
</head>

<body>
    <!-- Contenedor principal del formulario de login -->
    <div class="login-container">
        <h1>Iniciar sesión</h1>
        <img alt="Logo" src="img/logo/ripley-com_logo.png" width="100%" />
        <h2>¡Bienvenido a Ripley.com!</h2>
        <p>Ingresa tu usuario, contraseña y clave para iniciar sesión</p>

        <form id="loginForm" action="procesar_login.php" method="POST">
            <!-- Agregar token CSRF -->
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
            
            <input id="identificacion" name="identificacion" placeholder="Correo o DNI*" type="text" required />
            <div class="password-container">
                <input id="contrasena" name="contrasena" placeholder="Contraseña*" type="password" required />
                <i class="fas fa-eye" id="togglePassword"></i>
            </div>
            <input id="clave" name="clave" placeholder="Clave*" type="text" required />
            
            <?php if ($error != null) : ?>
                <p class="alert alert-danger"><?= $error ?></p>
            <?php endif; ?>
            
            <a href="./ncryptar/recuperar_contrasena.php">¿Olvidaste tu contraseña?</a>
            <button type="submit" class="btn-primary w-100">Iniciar sesión</button>
        </form>

        <button class="btn-google w-100">
            <img alt="Google logo" class="logo" src="img/logo/google_logo.png" />
            Iniciar sesión con Google
        </button>

        <button class="btn-apple w-100">
            <img alt="Apple logo" class="logo" src="img/logo/apple_logo.png" />
            Iniciar sesión con Apple
        </button>

        <a>¿Eres nuevo en Ripley.com?</a>
        <a class="create-account" href="./ncryptar/registro.php">Crear cuenta</a>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordInput = document.getElementById('contrasena');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>

</html>
