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
                    <a class="nav-link" href="intranet.php">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Productos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contacto</a>
                </li>
            </ul>
            
            <!-- Dropdown de usuario -->
            <div class="dropdown">
                <button class="btn btn-login dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <!-- Muestra el nombre del usuario aquí -->
                    <?php echo isset($_SESSION['nombre']) ? $_SESSION['nombre'] : "Iniciar Sesión"; ?>
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                    <?php if (isset($_SESSION['nombre'])): ?>
                        <a class="dropdown-item" href="mi_cuenta.php">Mi Cuenta</a>
                        <a class="dropdown-item" href="mis_compras.php">Mis Compras</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="logout.php">Cerrar Sesión</a>
                    <?php else: ?>
                        <a class="dropdown-item" href="login.php">Iniciar Sesión</a>
                        <a class="dropdown-item" href="registro.php">Registrarse</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Carrusel -->
    <div class="carousel-container">
        <!-- Contenido del carrusel aquí -->
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
            <!-- Tarjetas de productos aquí -->
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center py-4">
        <p>&copy; 2023 Ripley. Todos los derechos reservados.</p>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
