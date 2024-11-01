<?php
session_start();
$error = null;
if (isset($_GET["e"])) {
    $error = "Credenciales incorrectas";
}

// Generar una operación matemática simple
function generarCaptcha() {
    $numero1 = rand(1, 10);
    $numero2 = rand(1, 10);
    $_SESSION['captcha_result'] = $numero1 + $numero2;
    return [$numero1, $numero2];
}

list($numero1, $numero2) = generarCaptcha();
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
    <style>
        .captcha-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .captcha-box {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            text-align: center;
            width: 300px;
        }
        .captcha-box p {
            font-size: 18px;
            margin-bottom: 15px;
        }
        .captcha-box input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            font-size: 16px;
        }
        .captcha-box button {
            background-color: #6a1b9a;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .captcha-box button:hover {
            background-color: #5b2d8a;
        }
        .captcha-error {
            color: red;
            display: none;
        }
    </style>
</head>

<body>
    <!-- Overlay de CAPTCHA -->
    <div class="captcha-overlay" id="captchaOverlay">
        <div class="captcha-box">
            <p>Para continuar, resuelve la siguiente operación:</p>
            <p id="captchaQuestion"><strong>¿Cuánto es <?php echo $numero1; ?> + <?php echo $numero2; ?>?</strong></p>
            <input type="text" id="captchaInput" placeholder="Ingresa tu respuesta">
            <div class="captcha-error" id="captchaError">Respuesta incorrecta. Intenta de nuevo.</div>
            <button id="verifyCaptchaButton">Verificar</button>
        </div>
    </div>

    <!-- Contenedor principal del formulario de login -->
    <div class="login-container">
        <h1>Iniciar sesión</h1>
        <img alt="Logo" src="img/logo/ripley-com_logo.png" width="100%" />
        <h2>¡Bienvenido a Ripley.com!</h2>
        <p>Ingresa tu usuario, contraseña, clave y resuelve el CAPTCHA para iniciar sesión</p>

        <form id="loginForm" action="procesar_login.php" method="POST">
            <input id="identificacion" name="identificacion" placeholder="Correo o DNI*" type="text" required disabled />
            <div class="password-container">
                <input id="contrasena" name="contrasena" placeholder="Contraseña*" type="password" required disabled />
                <i class="fas fa-eye" id="togglePassword"></i>
            </div>
            <input id="clave" name="clave" placeholder="Clave*" type="text" required disabled />
            <!-- Campo oculto para el valor de CAPTCHA aprobado -->
            <input type="hidden" id="captchaHiddenInput" name="captcha" />
            <?php if ($error != null) : ?>
                <p class="alert alert-danger"><?= $error ?></p>
            <?php endif; ?>
            <a href="./ncryptar/recuperar_contrasena.php">¿Olvidaste tu contraseña?</a>
            <button type="submit" class="btn-primary w-100" id="submitButton" disabled>Iniciar sesión</button>
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
        document.getElementById('verifyCaptchaButton').addEventListener('click', function () {
            const captchaInput = document.getElementById('captchaInput').value;

            $.ajax({
                url: 'verificar_captcha.php', // Archivo PHP para verificar el CAPTCHA
                method: 'POST',
                data: { captcha: captchaInput },
                success: function (response) {
                    const data = JSON.parse(response);

                    if (data.success) {
                        // CAPTCHA correcto, ocultar el overlay y habilitar el formulario
                        document.getElementById('captchaOverlay').style.display = 'none';
                        document.getElementById('identificacion').disabled = false;
                        document.getElementById('contrasena').disabled = false;
                        document.getElementById('clave').disabled = false;
                        document.getElementById('submitButton').disabled = false;
                        document.getElementById('captchaHiddenInput').value = data.resultado; // Guardar el CAPTCHA aprobado
                    } else {
                        // CAPTCHA incorrecto, mostrar mensaje de error y actualizar operación
                        document.getElementById('captchaError').style.display = 'block';
                        document.getElementById('captchaQuestion').innerHTML = `<strong>¿Cuánto es ${data.numero1} + ${data.numero2}?</strong>`;
                    }
                }
            });
        });

        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordInput = document.getElementById('contrasena');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>

</html>
