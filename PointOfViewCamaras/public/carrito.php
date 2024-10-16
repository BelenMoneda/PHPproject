<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['idUsuario'])) {
    header("Location: login.php");  // Redirigir a la página de login si no ha iniciado sesión
    exit();
}

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "povcamaras";  
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Inicializar el carrito si no está creado
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Agregar producto al carrito
if (isset($_POST["idProducto"]) && isset($_POST["nombreProducto"]) && isset($_POST["precioUnitario"]) && isset($_POST["stock"])) {
    $idProducto = $_POST["idProducto"];
    $nombreProducto = $_POST["nombreProducto"];
    $precioUnitario = $_POST["precioUnitario"];
    $cantidad = isset($_POST["cantidad"]) ? (int)$_POST["cantidad"] : 1;

    // Buscar si el producto ya está en el carrito
    $producto_encontrado = false;
    foreach ($_SESSION['carrito'] as &$producto) {
        if ($producto['idProducto'] == $idProducto) {
            $producto['cantidad'] += $cantidad;  // Aumentar la cantidad si ya está en el carrito
            $producto_encontrado = true;
            break;
        }
    }

    // Si no se encontró, agregarlo al carrito
    if (!$producto_encontrado) {
        $_SESSION['carrito'][] = [
            'idProducto' => $idProducto,
            'nombreProducto' => $nombreProducto,
            'precioUnitario' => $precioUnitario,
            'cantidad' => $cantidad,
            'stock' => $_POST["stock"]
        ];
    }
}

// Eliminar producto del carrito
if (isset($_POST['eliminar']) && isset($_POST['idProducto'])) {
    foreach ($_SESSION['carrito'] as $key => $producto) {
        if ($producto['idProducto'] == $_POST['idProducto']) {
            unset($_SESSION['carrito'][$key]); // Eliminar el producto
            break;
        }
    }
}

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
</head>
<body>
    <h1>Mi Carrito de Compras</h1>

    <?php if (!empty($_SESSION['carrito'])): ?>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($_SESSION['carrito'] as $producto):
                    $subtotal = $producto['cantidad'] * $producto['precioUnitario'];
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($producto['nombreProducto']); ?></td>
                        <td><?php echo $producto['cantidad']; ?></td>
                        <td>$<?php echo number_format($producto['precioUnitario'], 2); ?></td>
                        <td>$<?php echo number_format($subtotal, 2); ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="idProducto" value="<?php echo $producto['idProducto']; ?>">
                                <input type="submit" name="eliminar" value="Eliminar">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p>Total: $<?php echo number_format($total, 2); ?></p>
        <form action="pedido.php" method="POST">
            <input type="submit" value="Realizar Pedido">
        </form>
    <?php else: ?>
        <p>El carrito está vacío.</p>
    <?php endif; ?>

</body>
</html>
