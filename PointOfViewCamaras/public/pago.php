<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "povcamaras";

// Conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificamos si se ha enviado el pedido para realizar el pago
if (isset($_GET['idPedido'])) {
    $idPedido = $_GET['idPedido'];

    // Obtener el precio total del pedido
    $sqlPrecio = "SELECT precioTotal FROM PEDIDO WHERE idPedido='$idPedido'";
    $resultPrecio = $conn->query($sqlPrecio);

    if ($resultPrecio->num_rows > 0) {
        $rowPrecio = $resultPrecio->fetch_assoc();
        $monto = $rowPrecio['precioTotal'];
    } else {
        die("No se encontró el pedido.");
    }

    // Verificamos si se ha enviado el formulario de pago
    if (isset($_POST['realizarPago'])) {
        $idMetodoPago = $_POST['metodoPago'];
        $estadoPago = "Pagado";
        $fechaPago = date("Y-m-d"); // Fecha actual

        // Insertar el pago en la tabla Pago
        $sqlInsertPago = "INSERT INTO Pago (idPedido, idMetodoPago, monto, estadoPago, fechaPago) 
                          VALUES ('$idPedido', '$idMetodoPago', '$monto', '$estadoPago', '$fechaPago')";

        if ($conn->query($sqlInsertPago) === TRUE) {
            // Actualizar el estado del pedido a "finalizado"
            $sqlUpdatePedido = "UPDATE PEDIDO SET estadoPedido='finalizado', estadoPago='$estadoPago' WHERE idPedido='$idPedido'";
            $conn->query($sqlUpdatePedido);

            // Actualizar el stock de los productos
            $sqlProductos = "SELECT idProducto, cantidad FROM LINEA_PEDIDO WHERE idPedido='$idPedido'";
            $resultProductos = $conn->query($sqlProductos);

            if ($resultProductos->num_rows > 0) {
                while ($rowProducto = $resultProductos->fetch_assoc()) {
                    $idProducto = $rowProducto['idProducto'];
                    $cantidad = $rowProducto['cantidad'];

                    // Actualizar el stock en la tabla Producto
                    $sqlUpdateStock = "UPDATE PRODUCTO SET stock = stock - $cantidad WHERE idProducto = '$idProducto'";
                    $conn->query($sqlUpdateStock);
                }
            }

            echo "<h2>Pedido realizado con éxito. ¡Gracias por tu compra!</h2>";
            echo "<p><a href='index.php'>Volver al inicio</a></p>";
        } else {
            echo "Error al registrar el pago: " . $conn->error;
        }
    }
} else {
    die("ID del pedido no proporcionado.");
}

// Obtener métodos de pago disponibles
$sqlMetodosPago = "SELECT * FROM MetodoPago";
$resultMetodosPago = $conn->query($sqlMetodosPago);

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
        .form-container { max-width: 400px; margin: auto; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group select, .form-group input { width: 100%; padding: 8px; }
        button { padding: 10px 15px; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Selecciona el Método de Pago</h2>
    <form action="pago.php?idPedido=<?php echo $idPedido; ?>" method="post">
        <div class="form-group">
            <label for="metodoPago">Método de Pago</label>
            <select name="metodoPago" id="metodoPago" required>
                <option value="">Selecciona un método</option>
                <?php if ($resultMetodosPago->num_rows > 0): ?>
                    <?php while ($rowMetodo = $resultMetodosPago->fetch_assoc()): ?>
                        <option value="<?php echo $rowMetodo['idMetodoPago']; ?>">
                            <?php echo $rowMetodo['nombreMetodoPago']; ?>
                        </option>
                    <?php endwhile; ?>
                <?php else: ?>
                    <option value="">No hay métodos de pago disponibles</option>
                <?php endif; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Monto a Pagar</label>
            <input type="text" value="$<?php echo number_format($monto, 2); ?>" disabled>
        </div>

        <button type="submit" name="realizarPago">Realizar Pago</button>
    </form>
</div>

</body>
</html>
