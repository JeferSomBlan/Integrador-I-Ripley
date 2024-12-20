<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Ncrypt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css'>
    <link rel="stylesheet" type="text/css" href="css/main.css">
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
</head>

<body>
    <?php require_once '../fragmentos/nc_header.php'; ?>

    <main class="container mt-4 form-section">
        <!-- Contenedor para Título y Descripción -->
        <section class="jumbotron text-center mb-4">
            <h1 class="display-4">Registro</h1>
            <p class="lead">Por favor, complete el siguiente formulario para registrarse.</p>
        </section>

        <!-- Formulario de Registro -->
        <form id="registroForm" action="procesar_registro.php" method="POST">
            <div class="row mb-3">
                <div class='col-md-6'>
                    <label for='nombre' class='form-label'><i class='bi bi-person-fill'></i> Nombre Completo</label>
                    <input type='text' class='form-control' id='nombre' name="nombre" required placeholder='Ingrese su nombre completo'>
                </div>
                <div class='col-md-6'>
                    <label for='correo' class='form-label'><i class='bi bi-envelope-fill'></i> Correo Electrónico</label>
                    <input type='email' class='form-control' id='correo' name="correo" required placeholder='Ingrese su correo electrónico'>
                </div>
                <div class='col-md-6'>
                    <label for='dni' class='form-label'><i class='bi bi-card-text'></i> DNI</label>
                    <input type='text' class='form-control' id='dni' name="dni" required placeholder='Ingrese su DNI'>
                </div>
                <div class='col-md-6'>
                    <label for='direccion' class='form-label'><i class='bi bi-house-fill'></i> Dirección</label>
                    <input type='text' class='form-control' id='direccion' name="direccion" required placeholder='Ingrese su dirección'>
                </div>
                <div class='col-md-6'>
                    <label for='telefono' class='form-label'><i class='bi bi-phone-fill'></i> Teléfono</label>
                    <input type='tel' class='form-control' id='telefono' name="telefono" required placeholder='Ingrese su teléfono'>
                </div>
                <div class='col-md-6'>
                    <label for='contraseña' class='form-label'><i class='bi bi-key-fill'></i> Contraseña</label>
                    <input type='password' class='form-control' id='contraseña' name="contrasena" required placeholder='Ingrese su contraseña'>
                </div>
            </div>
            <div class='d-flex align-items-center justify-content-center mb-3'>
                <input type='checkbox' class='form-check-input me-2' id='terminos' name="terminos" required>
                <label class='form-check-label' for='terminos'>Aceptar Términos y Condiciones</label>
            </div>
            <div class="btn-container">
                <button type="button" id="enviarBtn" class="btn btn-success">Aceptar</button>
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

    <!-- Modal para mostrar la clave generada -->
    <div class="modal fade" id="modalClave" tabindex="-1" aria-labelledby="modalClaveLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalClaveLabel">Clave Generada</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Tu clave de acceso es: <strong id="claveGenerada"></strong></p>
                    <button class="btn btn-outline-primary" onclick="copiarClave()">Copiar Clave</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Scripts de Bootstrap -->
    <script src='https://code.jquery.com/jquery-3.2.1.slim.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>
    
    <script>
        document.getElementById('enviarBtn').addEventListener('click', function (e) {
            e.preventDefault(); // Evita el envío normal del formulario

            const formData = new FormData(document.getElementById('registroForm'));

            // Enviar datos al servidor
            fetch('procesar_registro.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mostrar la clave en el modal
                    document.getElementById('claveGenerada').textContent = data.clave;
                    new bootstrap.Modal(document.getElementById('modalClave')).show();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });

        // Función para copiar la clave al portapapeles
        function copiarClave() {
            const clave = document.getElementById('claveGenerada').textContent;
            navigator.clipboard.writeText(clave).then(() => {
                alert('Clave copiada al portapapeles');
            });
        }
    </script>

</body>

</html>
