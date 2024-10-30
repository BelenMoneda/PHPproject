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

// Conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if (isset($_GET['idProducto'])) {
    $idProducto = $_GET['idProducto'];

    // Eliminar el producto
    $sql = "DELETE FROM PRODUCTO WHERE idProducto = '$idProducto'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idProducto);
    
    if ($stmt->execute()) {
        header("Location: gestion-productos.php"); // Redirigir después de eliminar
        exit();
    } else {
        die("Error al eliminar el producto.");
    }
} else {
    die("ID del producto no proporcionado.");
}

$conn->close();
?>
