// finalizar_compra.php
<?php
include_once './util/conexionMysql.php';

$data = json_decode(file_get_contents('php://input'), true);
if ($data && isset($data['carrito'])) {
    conectar();
    foreach ($data['carrito'] as $item) {
        $sql = "UPDATE productos SET stock = stock - {$item['cantidad']} WHERE id = {$item['id']}";
        ejecutar($sql);
    }
    desconectar();
    echo json_encode(['success' => true, 'message' => 'Compra finalizada']);
}
?>
