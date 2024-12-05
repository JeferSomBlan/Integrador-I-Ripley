<?php
include_once '../util/conexionMysql.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    conectar();

    $nombre = htmlspecialchars($_POST['nombre']);
    $correo = htmlspecialchars($_POST['correo']);
    $dni = htmlspecialchars($_POST['dni']);
    $direccion = htmlspecialchars($_POST['direccion']);
    $telefono = htmlspecialchars($_POST['telefono']);
    $contrasena = htmlspecialchars($_POST['contrasena']);

    $sql_verificar = "SELECT * FROM usuarios WHERE correo = '$correo' OR dni = '$dni'";
    $usuarios_existentes = consultar($sql_verificar);
    
    if (count($usuarios_existentes) > 0) {
        echo json_encode(['success' => false, 'message' => 'El correo o DNI ya están registrados']);
    } else {
        $contrasenaHash = password_hash($contrasena, PASSWORD_BCRYPT);
        $clave = bin2hex(random_bytes(16));
        $claveHash = password_hash($clave, PASSWORD_BCRYPT);

        $sql_insertar = "INSERT INTO usuarios (nombre, correo, dni, direccion, telefono, contrasena, clave) 
                        VALUES ('$nombre', '$correo', '$dni', '$direccion', '$telefono', '$contrasenaHash', '$claveHash')";

        if (ejecutar($sql_insertar)) {
            echo json_encode(['success' => true, 'clave' => $clave]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error en el registro']);
        }
    }

    desconectar();
}
?>