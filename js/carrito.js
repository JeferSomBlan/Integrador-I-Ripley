// Detectar si estamos en la carpeta 'intranet' o en la raíz del proyecto
const isIntranet = window.location.pathname.includes("intranet");
const listarProductosURL = isIntranet ? '../listar_productos.php' : 'listar_productos.php';

// Abre el modal del carrito
function abrirCarritoModal() {
    actualizarCarritoModal();
    $('#carritoModal').modal('show');
}

// Cierra el modal del carrito
function cerrarCarritoModal() {
    $('#carritoModal').modal('hide');
}

// Función para cargar productos
function cargarProductos() {
    fetch(listarProductosURL)
        .then(response => {
            if (!response.ok) {
                throw new Error("No se pudo cargar listar_productos.php. Verifica la ruta.");
            }
            return response.json();
        })
        .then(productos => {
            localStorage.setItem('productosDisponibles', JSON.stringify(productos));
            const container = document.getElementById('productos-container');
            if (container) {
                container.innerHTML = '';
                productos.forEach(producto => {
                    container.innerHTML += `
                        <div class="col-md-4">
                            <div class="card">
                                <img src="${isIntranet ? '../' : ''}${producto.imagen_url}" class="card-img-top" alt="${producto.nombre}">
                                <div class="card-body">
                                    <h5>${producto.nombre}</h5>
                                    <p>${producto.descripcion}</p>
                                    <p>Precio: S/ ${producto.precio}</p>
                                    <button onclick="agregarAlCarrito(${producto.id})" class="btn btn-primary">Agregar al Carrito</button>
                                </div>
                            </div>
                        </div>`;
                });
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