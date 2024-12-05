<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include_once '../util/conexionMysql.php';

// Conectar a la base de datos y obtener la información del usuario
$user_id = $_SESSION['user_id'];
conectar();

// Usar una consulta preparada para evitar inyección SQL
$sql = "SELECT * FROM usuarios WHERE id = ?";
if ($stmt = mysqli_prepare($cnx, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $user_id); // 'i' para enteros
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $usuario = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
} else {
    echo "Error al obtener datos del usuario.";
    exit();
}

desconectar();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta - Ripley</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="../img/logo/favicon.ico">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #343a40;
            padding: 10px 15px;
        }
        .navbar-brand, .navbar-nav .nav-link, .navbar-text {
            color: white !important;
        }
        .sidebar {
            background-color: #f8f9fa;
            min-height: 100vh;
            padding: 20px;
            width: 250px;
            border-right: 1px solid #ddd;
        }
        .sidebar a {
            color: #333;
            text-decoration: none;
            display: block;
            padding: 10px;
            margin-bottom: 5px;
        }
        .sidebar a.active {
            background-color: #6c757d;
            color: #fff;
        }
        .profile-container {
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="#"><img src="../img/logo/ripley_logo.png" alt="Logo" width="30"> Mi Cuenta</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <span class="navbar-text mr-3">¡Hola, <?php echo htmlspecialchars($usuario['nombre'], ENT_QUOTES, 'UTF-8'); ?>!</span>
        <a href="logout.php" class="btn btn-outline-light"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
    </div>
</nav>

<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="profile-header text-center">
            <span><?php echo strtoupper(substr(htmlspecialchars($usuario['nombre'], ENT_QUOTES, 'UTF-8'), 0, 1)); ?></span>
        </div>
        <a href="mis_compras.php">Mis compras realizadas</a>
        <a href="#">Contáctanos</a>
        <a href="mi_cuenta.php" class="active">Mi perfil</a>
        <a href="mi_cuenta.php">Mi contraseña</a>
        <a href="#">Mis direcciones</a>
        <a href="#">Pagar Tarjeta Ripley</a>
        <a href="#">Canje millas Latam</a>
        <a href="logout.php">Cerrar sesión</a>
    </div>

    <!-- Profile Content -->
    <div class="flex-grow-1 p-4">
        <div class="profile-container">
            <h4>Perfil</h4>
            <p>Aquí encontrarás tus datos personales</p>

            <?php if (isset($_GET['actualizado']) && $_GET['actualizado'] == 1): ?>
                <div class="alert alert-success" role="alert">
                    ¡Perfil actualizado con éxito!
                </div>
            <?php endif; ?>

            <form action="actualizar_perfil.php" method="POST">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="dni">DNI</label>
                        <input type="text" class="form-control" id="dni" value="<?php echo htmlspecialchars($usuario['dni'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($usuario['correo'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="telefono">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="direccion">Dirección</label>
                    <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo htmlspecialchars($usuario['direccion'], ENT_QUOTES, 'UTF-8'); ?>">
                </div>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
