<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encriptamiento - Ncrypt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css'> <!-- Bootstrap Icons -->
    <link rel="stylesheet" type="text/css" href="css/main.css"> <!-- Enlace al archivo CSS -->
    <style>
        body {
            margin: 0; /* Sin margen en el body */
            font-family: Arial, sans-serif; /* Fuente más profesional */
            background-color: #f8f9fa; /* Color de fondo claro */
        }
        header {
            background-color: #343a40; /* Fondo oscuro para el header */
            padding: 15px 0; /* Espaciado en el header */
        }
        .navbar-light .navbar-nav .nav-link {
            color: #f8f9fa; /* Color claro para las letras en el header */
        }
        .jumbotron {
            background-color: #495057; /* Fondo oscurecido para la sección de encriptamiento */
            color: #f8f9fa; /* Color de texto claro en la sección de encriptamiento */
            padding: 40px; /* Espaciado interno en la jumbotron */
            border-radius: 10px; /* Bordes redondeados */
            margin-bottom: 40px; /* Espacio inferior para separar del contenido siguiente */
        }
        .footer {
            background-color: #343a40; /* Fondo oscuro para el footer */
            padding: 20px 0; /* Espaciado en el footer */
            color: #f8f9fa; /* Color de texto claro en el footer */
        }
        .form-label {
            text-align: left; /* Alinear el texto del label a la izquierda */
            margin-bottom: 10px; /* Espacio inferior entre el label y el input */
            margin-top: 15px; /* Espacio superior para los labels */
        }
        .btn-container {
            display: flex;
            justify-content: center; /* Centrar el botón */
            margin: 30px; /* Espacio superior para el botón */
        }
    </style>
</head>
<body>
    <?php require_once '../fragmentos/nc_header.php'; ?>

    <main class="container mt-4 form-section">
        <!-- Contenedor para Título y Descripción -->
        <section class="jumbotron text-center mb-4">
            <h1 class="display-4">Encriptamiento</h1>
            <p class="lead">Por favor, ingrese su contraseña y clave para encriptar.</p>
        </section>

        <!-- Formulario de Encriptamiento -->
        <form>
            <div class='row mb-3'>
                <!-- Contenedor para los labels -->
                <div class='col-md-6'>
                    <label for='contrasena' class='form-label'><i class='bi bi-lock-fill'></i> Contraseña</label>
                    <input type='password' class='form-control' id='contrasena' required placeholder='Ingrese su contraseña'>
                </div>

                <div class='col-md-6'>
                    <label for='clave' class='form-label'><i class='bi bi-key-fill'></i> Clave</label>
                    <input type='text' class='form-control' id='clave' required placeholder='Ingrese su clave'>
                </div>
            </div>

            <!-- Botón Encriptar -->
            <div class='btn-container'>
                <button type='submit' class='btn btn-success'>Encriptar</button>
            </div>
        </form>

    </main>

    <!-- Footer -->
    <footer class='footer text-center'>
        <p><i class='bi bi-eye'></i> Ncrypt</p> 
        <p><a href='#' class='text-light'>Política de Privacidad</a> | 
           <a href='#' class='text-light'>Libro de Reclamaciones</a> | 
           <a href='#' class='text-light'>Portal de Estudiantes</a>
        </p> 
    </footer>

    <!-- Scripts de Bootstrap -->
    <script src='https://code.jquery.com/jquery-3.2.1.slim.min.js'></script> 
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js'></script> 
</body> 
</html>