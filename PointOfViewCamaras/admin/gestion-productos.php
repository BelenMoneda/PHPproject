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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $nombreProducto = $_POST['nombreProducto'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $descripcion = $_POST['descripcion'];
    $idCategoria = $_POST['idCategoria'];
    $precioUnitario = $_POST['precioUnitario'];
    $stock = $_POST['stock'];
    
    // Procesar la imagen cargada
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $nombreImagen = basename($_FILES["imagen"]["name"]);
        $rutaDestino = "../assets/images/productos/" . $nombreImagen;
        
        // Mover la imagen a la carpeta destino
        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaDestino)) {
            // Guardar la ruta de la imagen en la base de datos
            $imagen = $rutaDestino;

            $sql = "INSERT INTO PRODUCTO (nombreProducto, marca, modelo, descripcion, idCategoria, precioUnitario, stock, imagen) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssiids", $nombreProducto, $marca, $modelo, $descripcion, $idCategoria, $precioUnitario, $stock, $imagen);
            
            if ($stmt->execute()) {
                $successMessage = "Producto añadido correctamente.";
            } else {
                $errorMessage = "Error al añadir el producto.";
            }
        } else {
            $errorMessage = "Error al subir la imagen.";
        }
    } else {
        $errorMessage = "Debe seleccionar una imagen para el producto.";
    }
}

$sql = "SELECT * FROM PRODUCTO";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Productos</title>
    <link rel="stylesheet" href="../assets/css/gestion-productos.css">
</head>
<body>
    <h1>Gestion de Productos</h1>
    
    <h2>Añadir Producto</h2>
    <form method="post" action="gestion-productos.php" enctype="multipart/form-data">
        <label>Nombre:</label><br>
        <input type="text" name="nombreProducto" required><br>
        <label>Marca:</label><br>
        <input type="text" name="marca" required><br>
        <label>Modelo:</label><br>
        <input type="text" name="modelo" required><br>
        <label>Descripción:</label><br>
        <textarea name="descripcion" required></textarea><br>
        <label>Categoría:</label><br>
        <select name="idCategoria" required>
            <option value="">Selecciona una categoría</option>
            <option value="1">Cámaras</option>
            <option value="2">Accesorios</option>
        </select><br>
        <label>Precio Unitario:</label><br>
        <input type="number" name="precioUnitario" step="0.01" required><br>
        <label>Stock:</label><br>
        <input type="number" name="stock" required><br>
        <label>Imagen:</label><br>
        <input type="file" name="imagen" required><br>
        <input type="submit" name="add_product" value="Añadir Producto">
    </form>

    <?php if (isset($successMessage)) echo "<p>$successMessage</p>"; ?>
    <?php if (isset($errorMessage)) echo "<p>$errorMessage</p>"; ?>

    <h2>Editar o Eliminar Productos</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Imagen</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['idProducto']; ?></td>
                <td><?php echo $row['nombreProducto']; ?></td>
                <td><?php echo $row['marca']; ?></td>
                <td><?php echo $row['modelo']; ?></td>
                <td><?php echo $row['descripcion']; ?></td>
                <td><?php echo $row['precioUnitario']; ?></td>
                <td><?php echo $row['stock']; ?></td>
                <td><img src="<?php echo $row['imagen']; ?>" alt="Imagen del producto" width="80"></td>
                <td>
                    <a href="editar-producto.php?id=<?php echo $row['idProducto']; ?>">Editar</a>
                    <a href="eliminar-producto.php?id=<?php echo $row['idProducto']; ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este producto?');">Eliminar</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
