<?php


session_start();
// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['idUsuario'])) {
    header("Location: login.php");
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

$idUsuario = $_SESSION['idUsuario']; // ID del usuario (puedes adaptarlo según la sesión o autenticación)

// Verificar si se ha enviado un producto para modificar la cantidad
if (isset($_POST['modificarCantidad'])) {
    $idProducto = $_POST['idProducto'];
    $idPedido = $_POST['idPedido'];
    $accion = $_POST['accion']; // 'incrementar' o 'decrementar'

    // Obtenemos la cantidad actual
    $sqlCantidad = "SELECT cantidad FROM LINEA_PEDIDO WHERE idProducto='$idProducto' AND idPedido='$idPedido'";
    $resultCantidad = $conn->query($sqlCantidad);
    if ($resultCantidad->num_rows > 0) {
        $row = $resultCantidad->fetch_assoc();
        $cantidadActual = $row['cantidad'];

        if ($accion == 'incrementar') {
            $nuevaCantidad = $cantidadActual + 1;
        } elseif ($accion == 'decrementar' && $cantidadActual > 1) {
            $nuevaCantidad = $cantidadActual - 1;
        } else {
            $nuevaCantidad = $cantidadActual; // No hacer nada si es menor a 1
        }

        // Actualizamos la cantidad en la base de datos
        $sqlUpdateLineaPedido = "UPDATE LINEA_PEDIDO SET cantidad='$nuevaCantidad', subtotal=precioUnitario * '$nuevaCantidad' 
                                 WHERE idProducto='$idProducto' AND idPedido='$idPedido'";
        $conn->query($sqlUpdateLineaPedido);

        // Actualizamos el total del pedido
        $sqlUpdatePedido = "UPDATE PEDIDO SET precioTotal = (SELECT SUM(subtotal) FROM LINEA_PEDIDO WHERE idPedido='$idPedido') 
                            WHERE idPedido='$idPedido'";
        $conn->query($sqlUpdatePedido);
    }
}

// Verificar si existe un pedido activo para este usuario (estado 'comprando')
$sql = "SELECT idPedido FROM PEDIDO WHERE idUsuario='$idUsuario' AND estadoPedido='comprando'";
$result = $conn->query($sql);


if ($result->num_rows == 0) {
    
        echo "El carrito esta vacio";

}
else {

    $row = $result->fetch_assoc();
    $idPedido = $row['idPedido'];
}



// Verificar si se ha enviado un producto para agregar al carrito
if (isset($_POST['agregar'])) {
    $idProducto = $_POST['idProducto'];
    $cantidad = $_POST['cantidad'];
    $precioUnitario = $_POST['precioUnitario'];
    $subtotal = $precioUnitario * $cantidad;


    if ($result->num_rows == 0) {
        // Si no hay un pedido activo, creamos uno nuevo
        $sqlInsertPedido = "INSERT INTO PEDIDO (idUsuario, precioTotal, estadoPedido) 
                            VALUES ('$idUsuario', 0, 'comprando')";
        if ($conn->query($sqlInsertPedido) === TRUE) {
            $idPedido = $conn->insert_id; // Obtenemos el ID del pedido recién creado
        } else {
            echo "Error al crear el pedido: " . $conn->error;
            exit();
        }
    } else {
        // Si ya existe un pedido activo, obtenemos su ID
        $row = $result->fetch_assoc();
        $idPedido = $row['idPedido'];
    }

    // Insertamos la línea de pedido (producto añadido al carrito)
    $sqlInsertLineaPedido = "INSERT INTO LINEA_PEDIDO (idUsuario, cantidad, precioUnitario, idPedido, idProducto, subtotal) 
                             VALUES ('$idUsuario', '$cantidad', '$precioUnitario', '$idPedido', '$idProducto', '$subtotal')";

    if ($conn->query($sqlInsertLineaPedido) === TRUE) {
        echo "Producto añadido al carrito exitosamente.";
    } else {
        echo "Error al añadir producto al carrito: " . $conn->error;
    }

    // Actualizamos el total del pedido
    $sqlUpdatePedido = "UPDATE PEDIDO SET precioTotal = (SELECT SUM(subtotal) FROM LINEA_PEDIDO WHERE idPedido='$idPedido') 
                        WHERE idPedido='$idPedido'";
    $conn->query($sqlUpdatePedido);
}

// Mostramos los productos en el carrito
$sqlCarrito = "SELECT LP.idProducto, P.nombreProducto, LP.cantidad, LP.precioUnitario, LP.subtotal 
               FROM LINEA_PEDIDO LP 
               JOIN PRODUCTO P ON LP.idProducto = P.idProducto 
               WHERE LP.idPedido='$idPedido'";
$resultCarrito = $conn->query($sqlCarrito);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .carrito-container { margin: 20px; }
        .carrito-item { margin-bottom: 15px; }
        .total-container { margin-top: 20px; font-weight: bold; }
        .cantidad-buttons { display: inline-block; }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="carrito-container">
        <h2>Carrito de Compras</h2>
        <?php
        if ($resultCarrito->num_rows > 0) {
            while($row = $resultCarrito->fetch_assoc()) {
                echo "<div class='carrito-item'>";
                echo "<p>Producto: " . $row['nombreProducto'] . " | Cantidad: " . $row['cantidad'] . " | Precio Unitario: $" . number_format($row['precioUnitario'], 2) . " | Subtotal: $" . number_format($row['subtotal'], 2) . "</p>";

                // Botones para incrementar y decrementar la cantidad
                echo "<div class='cantidad-buttons'>";
                echo "<form action='' method='post' style='display:inline;'>";
                echo "<input type='hidden' name='idProducto' value='" . $row['idProducto'] . "'>";
                echo "<input type='hidden' name='idPedido' value='$idPedido'>";
                echo "<input type='hidden' name='accion' value='decrementar'>";
                echo "<button type='submit' name='modificarCantidad'>-</button>";
                echo "</form>";

                echo "<span> " . $row['cantidad'] . " </span>";

                echo "<form action='' method='post' style='display:inline;'>";
                echo "<input type='hidden' name='idProducto' value='" . $row['idProducto'] . "'>";
                echo "<input type='hidden' name='idPedido' value='$idPedido'>";
                echo "<input type='hidden' name='accion' value='incrementar'>";
                echo "<button type='submit' name='modificarCantidad'>+</button>";
                echo "</form>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>Tu carrito está vacío.</p>";
        }
        ?>
        <div class="total-container">
            <?php
            $sqlTotal = "SELECT precioTotal FROM PEDIDO WHERE idPedido='$idPedido'";
            $resultTotal = $conn->query($sqlTotal);
            if ($resultTotal->num_rows > 0) {
                $rowTotal = $resultTotal->fetch_assoc();
                echo "<p>Total del Pedido: $" . number_format($rowTotal['precioTotal'], 2) . "</p>";
            }
            ?>
        </div>

        <!-- Botón para realizar pedido -->
        <?php if ($resultCarrito->num_rows > 0): ?>
            <form action="pago.php" method="post">
                <input type="hidden" name="idPedido" value="<?php echo $idPedido; ?>">
                <button type="submit" name="realizarPedido">Realizar Pedido</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
<?php
$conn->close();
?>
