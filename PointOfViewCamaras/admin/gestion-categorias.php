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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Categorías</title>
</head>
<body>
    <h1>Gestionar Categorías</h1>
    
    <h2>Añadir Categoría</h2>
    <form method="post" action="gestion-categorias.php">
        <label>Nombre de la Categoría:</label><br>
        <input type="text" name="nombreCategoria" required><br>
        <input type="submit" name="add_category" value="Añadir Categoría">
    </form>

    <?php if (isset($successMessage)) echo "<p>$successMessage</p>"; ?>
    <?php if (isset($errorMessage)) echo "<p>$errorMessage</p>"; ?>

    <h2>Lista de Categorías</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre de la Categoría</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['idCategoria']; ?></td>
                <td><?php echo $row['nombreCategoria']; ?></td>
                <td>
                    <a href="editar-categoria.php?id=<?php echo $row['idCategoria']; ?>">Editar</a>
                    <a href="eliminar-categoria.php?id=<?php echo $row['idCategoria']; ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar esta categoría?');">Eliminar</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
