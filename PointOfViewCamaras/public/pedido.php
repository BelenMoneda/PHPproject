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
