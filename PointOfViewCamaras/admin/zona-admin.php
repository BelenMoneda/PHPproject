<?php
session_start();

if ($_SESSION['idRol'] != 1) {
    header("Location: ../public/login.php");
    exit(); 
}

$servername = "localhost"; 
$username = "root";        
$password = "";            
$dbname = "povcamaras";    

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="../assets/css/zona-admin.css">
</head>
<body>
    <h1>Bienvenido al Panel de Administración</h1>
    <nav>
        <a href="gestion-productos.php">Gestionar Productos</a>
        <a href="gestion-categorias.php">Gestionar Categorías</a>
        <a href="../public/login-destroy.php">Cerrar Sesión</a>
    </nav>
</body>
</html>


