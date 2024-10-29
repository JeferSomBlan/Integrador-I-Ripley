<?php
session_start();
session_unset();
session_destroy();
header("Location: ../index.php"); // Redirige a index.php después de cerrar sesión
exit();
