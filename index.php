<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ripley - Tienda de Moda</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="./img/logo/favicon.ico">
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
    <div class="modal" id="carritoModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tu Carrito</h5>
                    <button type="button" class="close" onclick="cerrarCarritoModal()">&times;</button>
                </div>
                <div class="modal-body" id="carritoContenido">
                    <p>Tu bolsa está vacía</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="cerrarCarritoModal()">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="finalizarCompra()">Finalizar Compra</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Footer -->
    <footer class='text-center py-4'>
        <p>&copy; 2023 Ripley. Todos los derechos reservados.</p>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js'></script> 
    <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js'></script> 

    <script>
// Abre el modal del carrito
function abrirCarritoModal() {
    actualizarCarritoModal(); // Actualiza el contenido del carrito antes de mostrarlo
    $('#carritoModal').modal('show'); // Muestra el modal usando Bootstrap
}

// Cierra el modal del carrito
function cerrarCarritoModal() {
    $('#carritoModal').modal('hide');
}

// Función para cargar productos
function cargarProductos() {
    fetch('listar_productos.php')
        .then(response => response.json())
        .then(productos => {
            if (productos.length > 0) {
                localStorage.setItem('productosDisponibles', JSON.stringify(productos));
                const container = document.getElementById('productos-container');
                container.innerHTML = '';
                productos.forEach(producto => {
                    container.innerHTML += `
                        <div class="col-md-4">
                            <div class="card">
                                <img src="${producto.imagen_url}" class="card-img-top" alt="${producto.nombre}">
                                <div class="card-body">
                                    <h5>${producto.nombre}</h5>
                                    <p>${producto.descripcion}</p>
                                    <p>Precio: S/ ${producto.precio}</p>
                                    <button onclick="agregarAlCarrito(${producto.id})" class="btn btn-primary">Agregar al Carrito</button>
                                </div>
                            </div>
                        </div>`;
                });
                console.log("Productos disponibles cargados en localStorage:", productos);
            } else {
                console.error("No se encontraron productos para mostrar.");
            }
        })
        .catch(error => console.error('Error al cargar productos:', error));
}

// Agregar producto al carrito
function agregarAlCarrito(idProducto) {
    const productosDisponibles = JSON.parse(localStorage.getItem('productosDisponibles')) || [];
    const producto = productosDisponibles.find(p => Number(p.id) === Number(idProducto));

    if (!producto) {
        console.warn(`Producto con ID ${idProducto} no encontrado en productos disponibles.`);
        return;
    }

    // Agregar o incrementar el producto en el carrito
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    const index = carrito.findIndex(p => p.id === idProducto);

    if (index > -1) {
        carrito[index].cantidad += 1;
    } else {
        carrito.push({ id: idProducto, cantidad: 1 });
    }

    localStorage.setItem('carrito', JSON.stringify(carrito));
    actualizarIconoCarrito();
    abrirCarritoModal();
}

// Actualiza el icono del carrito con la cantidad total de productos
function actualizarIconoCarrito() {
    const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    const totalCantidad = carrito.reduce((acc, item) => acc + item.cantidad, 0);
    const cartIcon = document.querySelector('.btn-cart .badge');

    if (cartIcon) {
        cartIcon.textContent = totalCantidad;
    } else if (totalCantidad > 0) {
        document.querySelector('.btn-cart').innerHTML += `<span class="badge badge-danger">${totalCantidad}</span>`;
    }
}

// Mostrar carrito en el modal
function actualizarCarritoModal() {
    const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    const productosDisponibles = JSON.parse(localStorage.getItem('productosDisponibles')) || [];
    const carritoContenido = document.getElementById('carritoContenido');

    if (carrito.length === 0) {
        carritoContenido.innerHTML = '<p>Tu bolsa está vacía</p>';
        return;
    }

    carritoContenido.innerHTML = '';
    let total = 0;

    carrito.forEach(item => {
        const producto = productosDisponibles.find(p => Number(p.id) === Number(item.id));
        if (producto) {
            carritoContenido.innerHTML += `
                <div class="d-flex justify-content-between">
                    <span>${producto.nombre} x ${item.cantidad}</span>
                    <span>S/ ${(producto.precio * item.cantidad).toFixed(2)}</span>
                </div>`;
            total += producto.precio * item.cantidad;
        } else {
            console.warn("Producto no encontrado en la lista de productos disponibles:", item.id);
        }
    });

    carritoContenido.innerHTML += `<hr><div class="d-flex justify-content-between"><strong>Total:</strong> <strong>S/ ${total.toFixed(2)}</strong></div>`;
}

// Finalizar compra
function finalizarCompra() {
    const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    if (carrito.length === 0) {
        alert('El carrito está vacío');
        return;
    }

    console.log("Compra finalizada:", carrito);
    alert('Gracias por tu compra');
    localStorage.removeItem('carrito');
    actualizarCarritoModal();
    actualizarIconoCarrito();
}

document.addEventListener('DOMContentLoaded', () => {
    cargarProductos();
    actualizarCarritoModal();
    actualizarIconoCarrito();
});

</script>

</body>
</html>