<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Ejemplo - Ncrypt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css'> <!-- Bootstrap Icons -->
    <style>
        body {
            margin: 0; /* Sin margen en el body */
            font-family: Arial, sans-serif; /* Fuente más profesional */
            background-color: #f8f9fa; /* Color de fondo claro */
        }
        header {
            background-color: #343a40; /* Fondo oscuro para el header */
            padding: 5px 0; /* Espaciado en el header */
        }
        .navbar-light .navbar-nav .nav-link {
            color: #f8f9fa; /* Color claro para las letras en el header */
        }
        .jumbotron {
            background-color: #495057; /* Fondo oscurecido para la sección de bienvenida */
            color: #f8f9fa; /* Color de texto claro en la sección de bienvenida */
            padding: 40px; /* Espaciado interno en la jumbotron */
            border-radius: 10px; /* Bordes redondeados */
        }
        .footer {
            background-color: #343a40; /* Fondo oscuro para el footer */
            padding: 20px 0; /* Espaciado en el footer */
            color: #f8f9fa; /* Color de texto claro en el footer */
        }
        .card {
            margin: 20px; /* Espacio entre las tarjetas */
            border-radius: 10px; /* Bordes redondeados para las tarjetas */
        }
    </style>
</head>
<body>
    <?php require_once '../fragmentos/nc_header.php'; ?>

    <main class="container mt-4">
        <section class="jumbotron text-center">
            <h1 class="display-4">Bienvenido a Ncrypt</h1>
            <p class="lead">Esta es una página de Encriptamiento.</p>
            <p><a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a></p>
        </section>

        <section class="row">
            <div class="col-md-4"> 
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Card Title 1</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
            </div>

            <div class='col-md-4'> 
                <div class='card shadow-sm'>
                    <div class='card-body'>
                        <h5 class='card-title'>Card Title 2</h5> 
                        <p class='card-text'>Some quick example text to build on the card title and make up the bulk of the card's content.</p> 
                        <a href='#' class='btn btn-primary'>Go somewhere</a> 
                    </div> 
                </div> 
            </div> 

            <div class='col-md-4'> 
                <div class='card shadow-sm'>
                    <div class='card-body'>
                        <h5 class='card-title'>Card Title 3</h5> 
                        <p class='card-text'>Some quick example text to build on the card title and make up the bulk of the card's content.</p> 
                        <a href='#' class='btn btn-primary'>Go somewhere</a> 
                    </div> 
                </div> 
            </div> 
        </section> 
    </main> 

    <footer class='footer text-center'>
        <p><i class='bi bi-eye'></i> Ncrypt</p> 
        <p><a href='#' class='text-light'>Política de Privacidad</a> | 
           <a href='#' class='text-light'>Libro de Reclamaciones</a> | 
           <a href='#' class='text-light'>Portal de Estudiantes</a></p> 
    </footer>

    <!-- Scripts de Bootstrap -->
    <script src='https://code.jquery.com/jquery-3.2.1.slim.min.js'></script> 
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js'></script> 
</body> 
</html>