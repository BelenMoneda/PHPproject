<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['idUsuario'])) {
    header("Location: login.php");
    exit();
}

// Verificar si el carrito tiene productos
if (empty($_SESSION['carrito'])) {
    echo "El carrito está vacío.";
    exit();
}

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "povcamaras";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener detalles del usuario
$idUsuario = $_SESSION['idUsuario'];
$sqlUsuario = "SELECT nombreUsuario, apellidos, email, direccion FROM USUARIOS WHERE idUsuario = ?";
$stmtUsuario = $conn->prepare($sqlUsuario);
$stmtUsuario->bind_param("i", $idUsuario);
$stmtUsuario->execute();
$resultUsuario = $stmtUsuario->get_result();
$usuario = $resultUsuario->fetch_assoc();
$stmtUsuario->close();

// Calcular el total del pedido
$total = 0;
foreach ($_SESSION['carrito'] as $producto) {
    $total += $producto['cantidad'] * $producto['precioUnitario'];
}

// Insertar el pedido en la base de datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $estadoPedido = 'Pendiente';
    $fechaPedido = date('Y-m-d');

    // Insertar en tabla Pedido
    $sqlPedido = "INSERT INTO PEDIDO (nombreUsuario, apellidos, email, direccion, precioTotal, estadoPedido, fechaPedido, idUsuario) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmtPedido = $conn->prepare($sqlPedido);
    $stmtPedido->bind_param(
        "ssssdssi", 
        $usuario['nombreUsuario'], 
        $usuario['apellidos'], 
        $usuario['email'], 
        $usuario['direccion'], 
        $total, 
        $estadoPedido, 
        $fechaPedido, 
        $idUsuario
    );
    $stmtPedido->execute();
    $idPedido = $stmtPedido->insert_id;  // Obtener el ID del pedido recién insertado
    $stmtPedido->close();

    // Insertar cada línea del pedido (productos en la tabla LINEA_PEDIDO)
    foreach ($_SESSION['carrito'] as $producto) {
        $cantidad = $producto['cantidad'];
        $precioUnitario = $producto['precioUnitario'];
        $subtotal = $cantidad * $precioUnitario;
        $idProducto = $producto['idProducto'];

        $sqlLineaPedido = "INSERT INTO LINEA_PEDIDO (cantidad, precioUnitario, idPedido, idProducto, subtotal) 
                           VALUES (?, ?, ?, ?, ?)";
        $stmtLineaPedido = $conn->prepare($sqlLineaPedido);
        $stmtLineaPedido->bind_param("idiii", $cantidad, $precioUnitario, $idPedido, $idProducto, $subtotal);
        $stmtLineaPedido->execute();
        $stmtLineaPedido->close();
    }

    // Vaciar el carrito después de confirmar el pedido
    unset($_SESSION['carrito']);

    // Redirigir a la página de pago
    header("Location: pago.php?idPedido=" . $idPedido);
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Pedido</title>
</head>
<body>
    <h1>Confirmar Pedido</h1>

    <h2>Detalles del Usuario</h2>
    <p>Nombre: <?php echo htmlspecialchars($usuario['nombreUsuario'] . ' ' . $usuario['apellidos']); ?></p>
    <p>Email: <?php echo htmlspecialchars($usuario['email']); ?></p>
    <p>Dirección: <?php echo htmlspecialchars($usuario['direccion']); ?></p>

    <h2>Resumen del Carrito</h2>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['carrito'] as $producto): ?>
                <tr>
                    <td><?php echo htmlspecialchars($producto['nombreProducto']); ?></td>
                    <td><?php echo $producto['cantidad']; ?></td>
                    <td>$<?php echo number_format($producto['precioUnitario'], 2); ?></td>
                    <td>$<?php echo number_format($producto['cantidad'] * $producto['precioUnitario'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p>Total a Pagar: $<?php echo number_format($total, 2); ?></p>

    <form method="POST" action="">
        <input type="submit" value="Confirmar Pedido">
    </form>
</body>
</html>
