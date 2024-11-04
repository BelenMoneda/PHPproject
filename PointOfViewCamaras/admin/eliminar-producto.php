<?php
session_start();
if ($_SESSION['idRol'] != 1) {
    header("Location: index.php");
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

if (isset($_GET['id'])) {
    $idProducto = $_GET['id'];

    $sql = "DELETE FROM PRODUCTO WHERE idProducto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idProducto);
    
    if ($stmt->execute()) {
        header("Location: gestion-productos.php"); 
        exit();
    } else {
        die("Error al eliminar el producto.");
    }
} else {
    die("ID del producto no proporcionado.");
}

$conn->close();
?>