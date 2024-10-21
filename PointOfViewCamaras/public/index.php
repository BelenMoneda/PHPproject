<!-- 1. Consulta de productos:
o Los usuarios podrán consultar los productos disponibles en la tienda.
o Los productos deben mostrarse con al menos: nombre, descripción,
precio, imagen y stock disponible.
2. Consulta de productos filtrados:
o Permitir a los usuarios buscar productos por distintas categorías
(ejemplo: ropa, electrónica), rango de precios, nombre y otras
características según el tipo de tienda. -->
<?php
$host = 'localhost'; 
$db = 'POVCamaras';  
$user = 'root';      
$pass = '';          

$conn = new mysqli($host, $user, $pass, $db);

$sql = "SELECT  P.idProducto, P.nombreProducto, P.descripcion, P.precioUnitario, P.imagen, P.stock, C.nombreCategoria 
        FROM PRODUCTO P
        JOIN CATEGORIA C ON P.idCategoria = C.idCategoria";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda de Cámaras</title>
    <link rel="stylesheet" href="../assets/css/index.css"> 
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <h1>Bienvenido a la tienda POV Cámaras</h1>
    
    <div class="productos">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='producto'>";
                echo "<form method='POST' action='detalle_producto.php'>";
                echo "<input type='hidden' name='idProducto' value='" . $row['idProducto'] . "'>";
                echo "<button type='submit'><img src='" . $row['imagen'] . "' alt='" . $row['nombreProducto'] . "' ></button>";
                echo "</form>";
                // echo "<img src='" . $row['imagen'] . "' alt='" . $row['nombreProducto'] . "'>";
                echo "<h4>" . $row['nombreProducto'] . "</h4>";
                echo "<p>Precio: $" . $row['precioUnitario'] . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No hay productos disponibles.</p>";
        }
        $conn->close();
        ?>
    </div>
</body>
</html>
