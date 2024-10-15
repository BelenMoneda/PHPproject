<!-- las imagenes, mostraremos la marca junto con el nombre de producto, id de producto pequeño debajo,  modelo, el precio, un texto con la descripcion, stok disponible y un boton de añadir el producto al carrito  -->

<?php
    if(isset($_GET['idProducto']))
    {
        $productoConsultado = $_GET['idProducto'];
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "povcamaras";
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }
        $sql = "SELECT idProducto, nombreProducto, marca, modelo, precioUnitario, descripcion, stock, imagen FROM producto WHERE idProducto='$productoConsultado'";
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
            echo "0 resultados";
        }

        $conn->close();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle_producto</title>
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="registro.php">Registrarse</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="detalle_producto.php">Productos</a></li>
        </ul>
    </nav>
    <div>
        <img src="<?php echo $imagen ?>" alt="imagen">
        <p>Nombre: <?php echo $nombreProducto ?></p>
        <p>ID: <?php echo $productoConsultado ?></p>
        <p>Marca: <?php echo $marca ?></p>
        <p>Modelo: <?php echo $modelo ?></p>
        <p>Precio: <?php echo $precioUnitario ?></p>
        <p>Descripcion: <?php echo $descripcion ?></p>
        <p>Stock: <?php echo $stock ?></p>
        <form action="carrito.php" method="post">
            <input type="hidden" name="idProducto" value="<?php echo $productoConsultado ?>">
            <input type="hidden" name="nombreProducto" value="<?php echo $nombreProducto ?>">
            <input type="hidden" name="precioUnitario" value="<?php echo $precioUnitario ?>">
            <input type="hidden" name="stock" value="<?php echo $stock ?>">
            <button type="submit">Añadir al carrito</button>
        </form>
    </div>

</body>
</html>