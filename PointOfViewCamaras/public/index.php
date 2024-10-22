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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rethink+Sans:ital,wght@0,400..800;1,400..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/index.css"> 
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="video-container">
        <video width="320" height="240" autoplay loop muted>
            <source src="../assets/video/video.mp4" type="video/mp4">
            Tu navegador no soporta la etiqueta de video.
        </video>
    </div>
    
    <hr>
    
    <h1>BIENVENIDOS A </h1>
    <h1>POINT OF VIEW CAMARAS</h1>
    
    <div class="productos">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='producto'>";
                echo "<form method='GET' action='detalle_producto.php' style='cursor: pointer;'>"; 
                echo "<input type='hidden' name='idProducto' value='" . $row['idProducto'] . "'>";
                echo "<button type='submit' style='border: none; background: none; width: 100%; padding: 0; text-align: left;'>"; 
                echo "<img src='" . $row['imagen'] . "' alt='" . $row['nombreProducto'] . "' style='width: 100%; height: auto; border-radius: 8px;'>";
                echo "<h4 style='margin: 10px 0;'>" . $row['nombreProducto'] . "</h4>";
                echo "<p style='color: #555;'>Precio: $" . $row['precioUnitario'] . "</p>";
                echo "</button>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "<p>No hay productos disponibles.</p>";
        }
        $conn->close();
        ?>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>

