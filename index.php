<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ripley - Tienda de Moda</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #d4edda;
        }
        .navbar-brand {
            color: #28a745 !important;
        }
        .btn-login {
            background-color: #28a745;
            color: white;
        }
        .hero {
            background-color: #28a745;
            color: white;
            padding: 50px 0;
            text-align: center;
            margin-bottom: 2rem;
        }
        .product-card {
            margin: 15px 0;
        }

        .nav-link {
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="#"><img src="img/logo/ripley_logo.png" alt="Logo" width="30"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Productos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contacto</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <!-- Cambiado el enlace del botón de iniciar sesión -->
                <button type="button" class="btn btn-login my-2 my-sm-0" onclick="location.href='login.php'">Iniciar Sesión</button>
            </form>
        </div>
    </nav>

    <!-- Carrusel -->
    <div class="carousel-container">
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="img/carrusel/carrusel1.png" class="d-block w-100" alt="Promoción 1">
                </div>
                <div class="carousel-item">
                    <img src="img/carrusel/Carrusel2.png" class="d-block w-100" alt="Promoción 2">
                </div>
                <div class="carousel-item">
                    <img src="img/carrusel/Carrusel3.png" class="d-block w-100" alt="Promoción 3">
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="hero">
        <h1>Bienvenido a Ripley</h1>
        <p>Descubre la mejor moda al mejor precio.</p>
        <a href="#productos" class="btn btn-light">Ver Productos</a>
    </div>

    <!-- Productos -->
    <div class="container" id="productos">
        <h2 class="text-center my-4">Nuestros Productos</h2>
        <div class="row">
            <!-- Producto 1 -->
            <div class="col-md-4 product-card">
                <div class="card">
                    <img src="img/producto/producto1.jpg" class="card-img-top" alt="Producto 1">
                    <div class="card-body">
                        <h5 class="card-title">Camisa de Moda</h5>
                        <p class="card-text">$29.99</p>
                        <a href="#" class="btn btn-primary">Agregar al Carrito</a>
                    </div>
                </div>
            </div>

            <!-- Producto 2 -->
            <div class='col-md-4 product-card'>
                <div class='card'>
                    <img src='img/producto/producto2.jpg' class='card-img-top' alt='Producto 2'>
                    <div class='card-body'>
                        <h5 class='card-title'>Pantalones Elegantes</h5>
                        <p class='card-text'>$39.99</p>
                        <a href='#' class='btn btn-primary'>Agregar al Carrito</a>
                    </div>
                </div>
            </div>

            <!-- Producto 3 -->
            <div class='col-md-4 product-card'>
                <div class='card'>
                    <img src='img/producto/producto3.jpg' class='card-img-top' alt='Producto 3'>
                    <div class='card-body'>
                        <h5 class='card-title'>Vestido de Noche</h5>
                        <p class='card-text'>$49.99</p>
                        <a href='#' class='btn btn-primary'>Agregar al Carrito</a>
                    </div>
                </div>
            </div>

        </div> <!-- End row -->
    </div> <!-- End container -->

    <!-- Footer -->
    <footer class='text-center py-4'>
        <p>&copy; 2023 Ripley. Todos los derechos reservados.</p>
    </footer>

    <!-- Scripts -->
    <script src='https://code.jquery.com/jquery-3.5.1.slim.min.js'></script> 
    <script src='https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js'></script> 
    <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js'></script> 

</body> 
</html> 