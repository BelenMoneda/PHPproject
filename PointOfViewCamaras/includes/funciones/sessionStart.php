<?php
    session_start();

    if (!isset($_SESSION['idUsuario'])) {
        header("Location: login.php");
        exit();
    }
?>
