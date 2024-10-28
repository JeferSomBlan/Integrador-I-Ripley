<?php
    session_start();
    if($_SESSION['acceso'] != '123456'){
        session_destroy();
        header('location: login.php');
    }