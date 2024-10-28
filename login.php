<?php
$error = null;
if (isset($_GET["e"])) {
    $error = "Credenciales incorrectas";
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
</head>

<body>
    <div class="login-container">
        <h1>Iniciar sesión</h1>
        <img alt="Logo" src="img/logo/ripley-com_logo.png" width="100%" />
        <h2>¡Bienvenido a Ripley.com!</h2>
        <p>Ingresa tu usuario, contraseña y clave para iniciar sesión</p>

        <form action="procesar_login.php" method="POST">
            <!-- Campo de correo o DNI -->
            <input id="identificacion" name="identificacion" placeholder="Correo o DNI*" type="text" required />

            <div class="password-container">
                <input id="contrasena" name="contrasena" placeholder="Contraseña*" type="password" required />
                <i class="fas fa-eye" id="togglePassword"></i>
            </div>

            <!-- Campo de clave -->
            <input id="clave" name="clave" placeholder="Clave*" type="text" required />
            
            <?php if ($error != null) : ?>
                <p class="alert alert-danger"><?= $error ?></p>
            <?php endif; ?>
            
            <a href="#">¿Olvidaste tu contraseña?</a>
            <button type="submit" class="btn-primary">Iniciar sesión</button>
        </form>

        <button class="btn-google">
            <img alt="Google logo" class="logo" src="img/logo/google_logo.png" />
            Iniciar sesión con Google
        </button>

        <button class="btn-apple">
            <img alt="Apple logo" class="logo" src="img/logo/apple_logo.png" />
            Iniciar sesión con Apple
        </button>

        <a>¿Eres nuevo en Ripley.com?</a>
        <a class="create-account" href="./ncryptar/registro.php">Crear cuenta</a>
    </div>


    <!-- Scripts -->
    <script src='https://code.jquery.com/jquery-3.5.1.slim.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js'></script>
    <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js'></script>

    <!-- Script para mostrar/ocultar contraseña -->
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