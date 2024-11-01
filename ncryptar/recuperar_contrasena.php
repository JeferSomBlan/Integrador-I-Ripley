<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña - Ripley</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css'>
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
            margin-top: 50px;
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

        .form-section {
            margin-top: 40px;
            margin-bottom: 40px;
        }
    </style>
</head>
<body>
    <?php require_once '../fragmentos/nc_header.php'; ?>
    <div class="container mt-5">
        <section class="jumbotron text-center">
            <h1 class="display-4">Recuperar Contraseña</h1>
            <p class="lead">Ingresa tu correo electrónico para recibir un enlace de recuperación.</p>
        </section>
        
        <form id="recuperacionForm">
            <div class="mb-3">
                <label for="correo" class="form-label">Correo Electrónico:</label>
                <input type="email" id="correo" name="correo" class="form-control" placeholder="ejemplo@correo.com" required>
            </div>
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary" id="enviarBtn">Enviar Enlace de Recuperación</button>
            </div>
        </form>
    </div>

    <?php require_once '../fragmentos/nc_footer.php'; ?>

    <!-- Modal de confirmación -->
    <div class="modal fade" id="modalConfirmacion" tabindex="-1" aria-labelledby="modalConfirmacionLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalConfirmacionLabel">Enlace de Recuperación Enviado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Se ha enviado un correo con el enlace para restablecer tu contraseña. Revisa tu bandeja de entrada.
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts de Bootstrap y JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('recuperacionForm').addEventListener('submit', function (e) {
            e.preventDefault(); // Evita el envío normal del formulario

            const formData = new FormData(this);

            fetch('procesar_recuperacion.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const modal = new bootstrap.Modal(document.getElementById('modalConfirmacion'));
                    modal.show();
                    setTimeout(() => {
                        modal.hide();
                        window.location.href = '../login.php';
                    }, 5000);
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>
