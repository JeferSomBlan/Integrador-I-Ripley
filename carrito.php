<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
    const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    if (carrito.length === 0) {
        document.getElementById('carrito-container').innerHTML = '<p>El carrito está vacío</p>';
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
                }
            });
            
            carritoContainer.innerHTML += `
                <div class="col-12">
                    <h4 class="text-right">Total: S/ ${total.toFixed(2)}</h4>
                </div>
            `;
        })
        .catch(error => console.error('Error al cargar productos del carrito:', error));
}


        // Finalizar compra
        function finalizarCompra() {
            alert('Gracias por su compra');
            localStorage.removeItem('carrito'); // Limpiar carrito después de la compra
            window.location.href = 'index.php'; // Redirigir a la página principal
        }

        // Cargar el carrito al cargar la página
        document.addEventListener('DOMContentLoaded', cargarCarrito);
    </script>
</body>
</html>
