<?php
include_once './util/conexionMysql.php';

function obtenerProductos() {
    conectar();
    $sql = "SELECT id, nombre, descripcion, precio, descuento, stock, imagen_url FROM productos";
    $productos = consultar($sql); // Ejecuta la consulta y devuelve el resultado
    desconectar();

    // Verificamos si la consulta devolvió productos
    if ($productos && is_array($productos)) {
        return $productos;
    } else {
        return []; // Devuelve un array vacío si no hay productos o hubo un error
    }
}

// Establecemos los encabezados para JSON y control de caché
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: 0');

// Convertimos el resultado a JSON y lo enviamos
echo json_encode(obtenerProductos());
?>
