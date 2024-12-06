<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://browser.sentry-cdn.com/7.43.0/bundle.min.js" integrity="sha384-LtAn3SHXnXDsvJMKZqxgdH9Uqwo1dOkxOmXd38KHXCvDQLlMGYCJJnLY67kF78ZB" crossorigin="anonymous"></script>
    <script>
        // Inicializar Sentry
        Sentry.init({ 
            dsn: 'https://50546abde49ec9c76f7562058fe9d492@o4508412475277312.ingest.us.sentry.io/4508417566638080',
            tracesSampleRate: 1.0
        });
    </script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Carrito de Compras</h2>
        <div id="carrito-container" class="row">
            <!-- Productos del carrito cargados aquí -->
        </div>
        <div class="d-flex justify-content-between mt-3">
            <button class="btn btn-secondary" onclick="window.location.href='index.php'">Seguir comprando</button>
            <button class="btn btn-primary" onclick="finalizarCompra()">Finalizar Compra</button>
        </div>
    </div>

    <script>
        // Cargar productos del carrito desde localStorage
        function cargarCarrito() {
            try {
                const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
                if (carrito.length === 0) {
                    document.getElementById('carrito-container').innerHTML = '<p>El carrito está vacío</p>';
                    console.log("El carrito está vacío");
                    Sentry.captureMessage("El carrito está vacío", 'info');
                    return;
                }

                fetch('listar_productos.php')
                    .then(response => response.json())
                    .then(productos => {
                        const carritoContainer = document.getElementById('carrito-container');
                        carritoContainer.innerHTML = '';
                        let total = 0;

                        carrito.forEach(idProducto => {
                            const producto = productos.find(p => p.id === idProducto);
                            if (producto) {
                                carritoContainer.innerHTML += `
                                    <div class="col-md-4">
                                        <div class="card mb-3">
                                            <img src="${producto.imagen_url}" class="card-img-top" alt="${producto.nombre}">
                                            <div class="card-body">
                                                <h5 class="card-title">${producto.nombre}</h5>
                                                <p class="card-text">S/ ${producto.precio}</p>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                total += parseFloat(producto.precio);
                            } else {
                                console.warn("Producto no encontrado en la lista de productos:", idProducto);

                                // Capturar advertencia en Sentry
                                Sentry.captureMessage(`Producto no encontrado. ID: ${idProducto}`, 'warning');

                                // Agregar log en el servidor en caso de producto no encontrado
                                fetch('log_producto_no_encontrado.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({ idProducto: idProducto, mensaje: "Producto no encontrado" })
                                })
                                .then(response => response.json())
                                .then(data => console.log(data.message))
                                .catch(error => {
                                    console.error('Error al registrar log:', error);
                                    Sentry.captureException(error);
                                });
                            }
                        });

                        carritoContainer.innerHTML += `
                            <div class="col-12">
                                <h4 class="text-right">Total: S/ ${total.toFixed(2)}</h4>
                            </div>
                        `;
                        console.log("Productos cargados correctamente en el carrito");
                        Sentry.captureMessage("Productos cargados correctamente en el carrito", 'info');
                    })
                    .catch(error => {
                        console.error('Error al cargar productos del carrito:', error);
                        Sentry.captureException(error);

                        // Log del error en el servidor
                        fetch('log_error_carrito.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ mensaje: error.message })
                        })
                        .then(response => response.json())
                        .then(data => console.log(data.message))
                        .catch(err => {
                            console.error('Error al registrar log del error:', err);
                            Sentry.captureException(err);
                        });
                    });
            } catch (error) {
                console.error('Error inesperado al cargar el carrito:', error);
                Sentry.captureException(error);
            }
        }

        // Finalizar compra
        function finalizarCompra() {
            try {
                alert('Gracias por su compra');
                console.log("Compra finalizada");
                Sentry.captureMessage("Compra finalizada", 'info');
                localStorage.removeItem('carrito'); // Limpiar carrito después de la compra
                window.location.href = 'index.php'; // Redirigir a la página principal
            } catch (error) {
                console.error('Error al finalizar la compra:', error);
                Sentry.captureException(error);
            }
        }

        // Cargar el carrito al cargar la página
        document.addEventListener('DOMContentLoaded', cargarCarrito);
    </script>
</body>
</html>
