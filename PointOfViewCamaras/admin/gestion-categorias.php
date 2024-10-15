<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

require_once 'db_connection.php'; // Incluye archivo de conexión a la base de datos

// Añadir categoría
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_category'])) {
    $nombreCategoria = $_POST['nombreCategoria'];

    $sql = "INSERT INTO CATEGORIA (nombreCategoria) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nombreCategoria);
    
    if ($stmt->execute()) {
        $successMessage = "Categoría añadida correctamente.";
    } else {
        $errorMessage = "Error al añadir la categoría.";
    }
}

// Listar categorías
$sql = "SELECT * FROM CATEGORIA";
$result = $conn->query($sql);
?>

<!DOCTYPE
