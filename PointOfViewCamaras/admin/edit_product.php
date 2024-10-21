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
    $idProducto = $_GET['id'];

    // Obtener los datos del producto
    $sql = "SELECT * FROM PRODUCTO WHERE idProducto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idProducto);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("Producto no encontrado.");
    }

    $producto = $result->fetch_assoc();
}

// Editar producto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_product'])) {
    $nombreProducto = $_POST['nombreProducto'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $descripcion = $_POST['descripcion'];
    $idCategoria = $_POST['idCategoria'];
    $precioUnitario = $_POST['precioUnitario'];
    $stock = $_POST['stock'];
    $imagen = $_POST['imagen']; // Suponiendo que se maneja el archivo adecuadamente

    $sql = "UPDATE PRODUCTO SET nombreProducto=?, marca=?, modelo=?, descripcion=?, idCategoria=?, precioUnitario=?, stock=?, imagen=? WHERE idProducto=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssiidsi", $nombreProducto, $marca, $modelo, $descripcion, $idCategoria, $precioUnitario, $stock, $imagen, $idProducto);
    
    if ($stmt->execute()) {
        header("Location: gestion-productos.php"); // Redirigir después de editar
        exit();
    } else {
        $errorMessage = "Error al actualizar el producto.";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
</head>
<body>
    <h1>Editar Producto</h1>
    
    <form method="post" action="edit_product.php?id=<?php echo $idProducto; ?>">
        <label>Nombre:</label><br>
        <input type="text" name="nombreProducto" value="<?php echo $producto['nombreProducto']; ?>" required><br>
        <label>Marca:</label><br>
        <input type="text" name="marca" value="<?php echo $producto['marca']; ?>" required><br>
        <label>Modelo:</label><br>
        <input type="text" name="modelo" value="<?php echo $producto['modelo']; ?>" required><br>
        <label>Descripción:</label><br>
        <textarea name="descripcion" required><?php echo $producto['descripcion']; ?></textarea><br>
        <label>Categoría:</label><br>
        <select name="idCategoria" required>
            <option value="1" <?php echo ($producto['idCategoria'] == 1) ? 'selected' : ''; ?>>Cámaras</option>
            <option value="2" <?php echo ($producto['idCategoria'] == 2) ? 'selected' : ''; ?>>Accesorios</option>
        </select><br>
        <label>Precio Unitario:</label><br>
        <input type="number" name="precioUnitario" value="<?php echo $producto['precioUnitario']; ?>" step="0.01" required><br>
        <label>Stock:</label><br>
        <input type="number" name="stock" value="<?php echo $producto['stock']; ?>" required><br>
        <div class="form-group">
            <label for="imagen">Imagen:</label>
            <input type="file" name="imagen" id="imagen" accept="image/*">
        </div>
        <input type="submit" name="edit_product" value="Actualizar Producto">
    </form>

    <?php if (isset($errorMessage)) echo "<p>$errorMessage</p>"; ?>
</body>
</html>

<?php
$conn->close();
?>
