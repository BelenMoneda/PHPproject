<!-- 1. Consulta de productos:
o Los usuarios podrán consultar los productos disponibles en la tienda.
o Los productos deben mostrarse con al menos: nombre, descripción,
precio, imagen y stock disponible.
2. Consulta de productos filtrados:
o Permitir a los usuarios buscar productos por distintas categorías
(ejemplo: ropa, electrónica), rango de precios, nombre y otras
características según el tipo de tienda. -->
<?php
// conexión a la base de datos
$host = 'localhost'; // Cambia por tu host si es diferente
$db = 'POVCamaras';  // Nombre de tu base de datos
$user = 'root';      // Usuario de la base de datos
$pass = '';          // Contraseña del usuario

// Crear conexión
$conn = new mysqli($host, $user, $pass, $db);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Filtrado de productos
$whereClauses = [];
if (isset($_GET['categoria']) && $_GET['categoria'] != '') {
    $categoria = intval($_GET['categoria']);
    $whereClauses[] = "P.idCategoria = $categoria";
}

if (isset($_GET['precio_min']) && $_GET['precio_min'] != '') {
    $precioMin = floatval($_GET['precio_min']);
    $whereClauses[] = "P.precioUnitario >= $precioMin";
}

if (isset($_GET['precio_max']) && $_GET['precio_max'] != '') {
    $precioMax = floatval($_GET['precio_max']);
    $whereClauses[] = "P.precioUnitario <= $precioMax";
}

if (isset($_GET['nombre']) && $_GET['nombre'] != '') {
    $nombre = $conn->real_escape_string($_GET['nombre']);
    $whereClauses[] = "P.nombreProducto LIKE '%$nombre%'";
}

// Consulta SQL para obtener productos
$sql = "SELECT P.idProducto, P.nombreProducto, P.descripcion, P.precioUnitario, P.imagen, P.stock, C.nombreCategoria 
        FROM PRODUCTO P
        JOIN CATEGORIA C ON P.idCategoria = C.idCategoria";

// Si hay filtros, añadirlos a la consulta
if (count($whereClauses) > 0) {
    $sql .= " WHERE " . implode(' AND ', $whereClauses);
}

// Ejecutar consulta filtrada
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

    <form method="GET" action="productos.php">
        <label for="categoria">Categoría:</label>
        <select name="categoria" id="categoria">
            <option value="">Todas</option>
            <?php

            $catSql = "SELECT idCategoria, nombreCategoria FROM CATEGORIA";
            $catResult = $conn->query($catSql);
            if ($catResult->num_rows > 0) {
                while ($catRow = $catResult->fetch_assoc()) {
                    echo "<option value='" . $catRow['idCategoria'] . "'>" . $catRow['nombreCategoria'] . "</option>";
                }
            }
            ?>
        </select>

        <label for="precio_min">Precio mínimo:</label>
        <input type="number" name="precio_min" id="precio_min" step="0.01" min="0">

        <label for="precio_max">Precio máximo:</label>
        <input type="number" name="precio_max" id="precio_max" step="0.01" min="0">

        <label for="nombre">Buscar por nombre:</label>
        <input type="text" name="nombre" id="nombre" placeholder="Nombre del producto">

        <input type="submit" value="Filtrar">
    </form>

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
</body>
</html>
