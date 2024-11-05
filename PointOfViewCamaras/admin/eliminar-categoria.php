<?php
include '../includes/funciones/sessionStart.php';

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
    $idCategoria = $_GET['id'];

    $sql = "UPDATE PRODUCTO SET idCategoria = NULL WHERE idCategoria = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idCategoria);
    $stmt->execute();

    $sql = "DELETE FROM CATEGORIA WHERE idCategoria = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idCategoria);
    
    if ($stmt->execute()) {
        header("Location: gestion-categorias.php");
        exit();
    } else {
        echo "Error al eliminar la categoría.";
    }
}

$conn->close();
?>
