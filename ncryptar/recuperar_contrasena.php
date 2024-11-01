<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña - Ripley</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

        .form-check {
            margin-top: 15px;
        }

        .form-section {
            margin-top: 40px;
            margin-bottom: 40px;
        }
    </style>
    </style>
</head>
<body>
    <?php require_once '../fragmentos/nc_header.php'; ?>
    <div class="container mt-5">
        <h2 class="text-center">Recuperar Contraseña</h2>
        <p class="text-center">Ingresa tu correo electrónico o DNI para recibir un enlace de recuperación.</p>
        
        <form action="procesar_recuperacion.php" method="POST">
            <div class="mb-3">
                <label for="identificacion" class="form-label">Correo o DNI:</label>
                <input type="text" id="identificacion" name="identificacion" class="form-control" required>
            </div>
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary">Enviar Enlace de Recuperación</button>
            </div>
        </form>
    </div>
    <footer class='footer text-center'>
        <p><i class='bi bi-eye'></i> Ncrypt</p> 
        <p><a href='#' class='text-light'>Política de Privacidad</a> | 
           <a href='#' class='text-light'>Libro de Reclamaciones</a> | 
           <a href='#' class='text-light'>Portal de Estudiantes</a></p> 
    </footer>
</body>
</html>
