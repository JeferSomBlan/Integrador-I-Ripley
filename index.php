<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ripley - Tienda de Moda</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #d4edda;
            padding: 10px 15px;
        }
        .navbar-brand img {
            width: 50px;
        }
        .form-inline .form-control {
            border-radius: 20px;
            width: 300px;
        }
        .btn-search {
            color: #28a745;
            border: 1px solid #28a745;
            border-radius: 50%;
            padding: 6px 10px;
            margin-left: -35px;
            background-color: #fff;
        }
        .navbar-nav .nav-item {
            display: flex;
            align-items: center;
        }
        .navbar-nav .nav-link {
            color: #28a745;
            font-weight: 500;
            margin-left: 15px;
            display: flex;
            align-items: center;
        }
        .btn-cart {
            color: #28a745;
            font-size: 1.4rem;
            margin-left: 15px;
        }
        .hero {
            background-color: #28a745;
            color: white;
            padding: 50px 0;
            text-align: center;
            margin-bottom: 2rem;
        }
        .product-card .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }
        .product-card .card-text,
        .product-card .card-title {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <!-- Logo -->
        <a class="navbar-brand" href="#"><img src="img/logo/ripley_logo.png" alt="Logo"></a>
        
        <!-- Menu -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
            <!-- Ingresar ubicación -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-map-marker-alt"></i> Ingresar tu ubicación
                    </a>
                </li>
            </ul>
            
            <!-- Barra de búsqueda centrada -->
            <form class="form-inline mx-auto">
                <input class="form-control" type="search" placeholder="Buscar Productos" aria-label="Search">
                <button class="btn btn-search" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            
            <!-- Íconos de usuario y carrito -->
            <ul class="navbar-nav">
                <!-- Botón o Dropdown de usuario -->
                <?php if (isset($_SESSION['nombre'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            ¡Hola, <?php echo htmlspecialchars($_SESSION['nombre']); ?>!
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="mi_cuenta.php">Mi Cuenta</a>
                            <a class="dropdown-item" href="mis_compras.php">Mis Compras</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="logout.php">Cerrar Sesión</a>
                        </div>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <button class="btn btn-login" onclick="location.href='login.php'">Iniciar Sesión</button>
                    </li>
                <?php endif; ?>
                
                <!-- Ícono de Carrito -->
                <li class="nav-item">
                    <a href="#" class="nav-link btn-cart" onclick="abrirCarritoModal()">
                        <i class="fas fa-shopping-cart"></i><span class="badge badge-danger"></span>
                    </a>
                </li>
            </ul>
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
        <div class="row" id="productos-container">
            <!-- Productos cargados dinámicamente -->
        </div>
    </div>

    <!-- Modal Carrito -->
    <div class="modal" id="carritoModal" tabindex="-1" role="dialog" style="position: fixed; top: 0; right: 0; width: 300px; height: 100vh; margin: 0;">
        <div class="modal-dialog modal-dialog-scrollable" role="document" style="width: 100%; height: 100%;">
            <div class="modal-content" style="height: 100%; display: flex; flex-direction: column;">
                <div class="modal-header">
                    <h5 class="modal-title">Tu Carrito</h5>
                    <button type="button" class="close" onclick="cerrarCarritoModal()">&times;</button>
                </div>
                <div class="modal-body" id="carritoContenido">
                    <p>Tu bolsa está vacía</p>
                </div>
                <div class="modal-footer" style="margin-top: auto;">
                    <button type="button" class="btn btn-secondary" onclick="cerrarCarritoModal()">Cerrar</button>
                    <a href="carrito.php" class="btn btn-primary">Ir al carro</a>
                </div>
            </div>
        </div>
    </div>


    <!-- Footer -->
    <footer class='text-center py-4'>
        <p>&copy; 2023 Ripley. Todos los derechos reservados.</p>
    </footer>

    <!-- Scripts -->
    <script src='https://code.jquery.com/jquery-3.5.1.slim.min.js'></script> 
    <script src='https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js'></script> 
    <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js'></script> 

    <script>
    // Función para cargar productos desde listar_productos.php
// Función para cargar productos desde listar_productos.php
function cargarProductos() {
    fetch('listar_productos.php')
        .then(response => response.json())
        .then(productos => {
            const container = document.getElementById('productos-container');
            container.innerHTML = ''; // Limpiar contenido previo
            productos.forEach(producto => {
                const descuento = producto.descuento ? `<span class="badge badge-danger">-${producto.descuento}%</span>` : '';
                container.innerHTML += `
                    <div class="col-md-4 product-card">
                        <div class="card">
                            <img src="${producto.imagen_url}" class="card-img-top" alt="${producto.nombre}">
                            <div class="card-body">
                                <h5 class="card-title">${producto.nombre} ${descuento}</h5>
                                <p class="card-text">${producto.descripcion}</p>
                                <p class="card-text">S/ ${producto.precio}</p>
                                <div class="mt-auto">
                                    <button class="btn btn-primary btn-block" onclick="agregarAlCarrito(${producto.id})">Agregar al Carrito</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            // Guardar los productos en localStorage para usarlos en el carrito
            localStorage.setItem('productos', JSON.stringify(productos));
        })
        .catch(error => console.error('Error al cargar productos:', error));
}

// Llamar a cargarProductos al cargar la página
document.addEventListener('DOMContentLoaded', cargarProductos);

// Función para agregar al carrito
function agregarAlCarrito(idProducto) {
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    
    if (!carrito.includes(idProducto)) {
        carrito.push(idProducto);
    }
    
    localStorage.setItem('carrito', JSON.stringify(carrito));
    actualizarIconoCarrito();
    abrirCarritoModal(); // Abre el carrito al agregar
}

// Actualizar icono del carrito
function actualizarIconoCarrito() {
    const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    const cartIcon = document.querySelector('.btn-cart .badge');
    if (cartIcon) {
        cartIcon.remove();
    }
    if (carrito.length > 0) {
        document.querySelector('.btn-cart').innerHTML += `<span class="badge badge-danger">${carrito.length}</span>`;
    }
}

// Abrir el modal del carrito y mostrar su contenido
function abrirCarritoModal() {
    const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    const carritoContenido = document.getElementById('carritoContenido');
    const productos = JSON.parse(localStorage.getItem('productos')) || [];
    
    if (carrito.length === 0) {
        carritoContenido.innerHTML = '<p>Tu bolsa está vacía</p>';
    } else {
        carritoContenido.innerHTML = '';
        let total = 0;
        
        carrito.forEach(idProducto => {
            const producto = productos.find(p => p.id === idProducto);
            if (producto) {
                carritoContenido.innerHTML += `
                    <div class="cart-item d-flex justify-content-between">
                        <span>${producto.nombre}</span>
                        <span>S/ ${producto.precio}</span>
                    </div>
                `;
                total += parseFloat(producto.precio);
            } else {
                console.warn("Producto no encontrado en la lista de productos:", idProducto);
            }
        });
        
        carritoContenido.innerHTML += `
            <hr>
            <div class="d-flex justify-content-between">
                <strong>Total:</strong>
                <strong>S/ ${total.toFixed(2)}</strong>
            </div>
        `;
        console.log("Total calculado:", total);
    }
    
    $('#carritoModal').modal('show');
}


// Cerrar el modal del carrito
function cerrarCarritoModal() {
    $('#carritoModal').modal('hide');
}

// Inicializar icono del carrito al cargar la página
document.addEventListener('DOMContentLoaded', actualizarIconoCarrito);

</script>

</body>
</html>