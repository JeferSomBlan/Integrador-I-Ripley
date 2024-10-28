<?php
include_once 'util/conexion_mysql.php';
$sql = "SELECT * FROM empleado";
try {
   conectar();
   $datos = consultar($sql);
   var_dump($datos);
   desconectar();
} catch (Exception $ex) {
   die($ex->getMessage());
}