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

if (isset($_GET['id'])) {
    $idCategoria = $_GET['id'];

    // Obtener los datos de la categoría
    $sql = "SELECT * FROM CATEGORIA WHERE idCategoria = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idCategoria);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("Categoría no encontrada.");
    }

    $categoria = $result->fetch_assoc();
}

// Editar categoría
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_category'])) {
    $nombreCategoria = $_POST['nombreCategoria'];

    $sql = "UPDATE CATEGORIA SET nombreCategoria=? WHERE idCategoria=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $nombreCategoria, $idCategoria);
    
    if ($stmt->execute()) {
        header("Location: gestion-categorias.php"); // Redirigir después de editar
        exit();
    } else {
        $errorMessage = "Error al actualizar la categoría.";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categoría</title>
</head>
<body>
    <h1>Editar Categoría</h1>
    
    <form method="post" action="editar-categoria.php?id=<?php echo $idCategoria; ?>">
        <label>Nombre de la Categoría:</label><br>
        <input type="text" name="nombreCategoria" value="<?php echo $categoria['nombreCategoria']; ?>" required><br>
        <input type="submit" name="edit_category" value="Actualizar Categoría">
    </form>

    <?php if (isset($errorMessage)) echo "<p>$errorMessage</p>"; ?>
</body>
</html>

<?php
$conn->close();
?>
