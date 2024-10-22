<?php
if(isset($_GET['idProducto'])) {
    $productoConsultado = $_GET['idProducto'];
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "povcamaras";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $sql = "SELECT idProducto, nombreProducto, marca, modelo, precioUnitario, descripcion, stock, imagen 
            FROM PRODUCTO WHERE idProducto='$productoConsultado'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $nombreProducto = $row["nombreProducto"];
            $marca = $row["marca"];
            $modelo = $row["modelo"];
            $precioUnitario = $row["precioUnitario"];
            $descripcion = $row["descripcion"];
            $stock = $row["stock"];
            $imagen = $row["imagen"];
        }
    } else {
        echo "No se encontraron resultados para el producto.";
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Producto</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div>
        <img src="<?php echo $imagen ?>" alt="imagen del producto" width="300">
        <p><strong>Nombre:</strong> <?php echo $nombreProducto ?></p>
        <p><strong>ID:</strong> <?php echo $productoConsultado ?></p>
        <p><strong>Marca:</strong> <?php echo $marca ?></p>
        <p><strong>Modelo:</strong> <?php echo $modelo ?></p>
        <p><strong>Precio:</strong> $<?php echo number_format($precioUnitario, 2) ?></p>
        <p><strong>Descripción:</strong> <?php echo $descripcion ?></p>
        <p><strong>Stock disponible:</strong> <?php echo $stock ?></p>

        <?php if ($stock > 0): ?>
            <form action="carrito.php" method="post">
                <input type="hidden" name="idProducto" value="<?php echo $productoConsultado ?>">
                <input type="hidden" name="nombreProducto" value="<?php echo $nombreProducto ?>">
                <input type="hidden" name="precioUnitario" value="<?php echo $precioUnitario ?>">
                <input type="number" name="cantidad" value="1" min="1" max="<?php echo $stock ?>" required>
                <button type="submit" name="agregar">Añadir al carrito</button>
            </form>
        <?php else: ?>
            <p>Producto agotado</p>
        <?php endif; ?>
    </div>

</body>
</html>
