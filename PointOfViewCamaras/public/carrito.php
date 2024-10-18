<?php
session_start(); // Iniciar la sesión para gestionar el carrito

// Conectar a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "povcamaras";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Inicializamos el carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = array();
}

// Función para encontrar un producto en el carrito por su ID
function encontrarProductoEnCarrito($idProducto) {
    foreach ($_SESSION['carrito'] as $key => $item) {
        if ($item['idProducto'] == $idProducto) {
            return $key;
        }
    }
    return false;
}

// Acción para agregar productos al carrito
if (isset($_POST['agregar'])) {
    $idProducto = $_POST['idProducto'];
    $nombreProducto = $_POST['nombreProducto'];
    $precioUnitario = $_POST['precioUnitario'];
    $cantidad = $_POST['cantidad'];

    // Comprobar si el producto ya está en el carrito
    $index = encontrarProductoEnCarrito($idProducto);
    if ($index === false) {
        // Si no está en el carrito, lo agregamos
        $_SESSION['carrito'][] = array(
            'idProducto' => $idProducto,
            'nombreProducto' => $nombreProducto,
            'precioUnitario' => $precioUnitario,
            'cantidad' => $cantidad
        );
    } else {
        // Si ya está en el carrito, actualizamos la cantidad
        $_SESSION['carrito'][$index]['cantidad'] += $cantidad;
    }
}

// Acción para actualizar la cantidad de un producto en el carrito
if (isset($_POST['actualizar'])) {
    $idProducto = $_POST['idProducto'];
    $nuevaCantidad = $_POST['cantidad'];

    // Encontramos el producto en el carrito y actualizamos la cantidad
    $index = encontrarProductoEnCarrito($idProducto);
    if ($index !== false) {
        $_SESSION['carrito'][$index]['cantidad'] = $nuevaCantidad;
    }
}

// Acción para eliminar un producto del carrito
if (isset($_POST['eliminar'])) {
    $idProducto = $_POST['idProducto'];

    // Encontramos el producto en el carrito y lo eliminamos
    $index = encontrarProductoEnCarrito($idProducto);
    if ($index !== false) {
        unset($_SESSION['carrito'][$index]);
        // Reorganizamos el array para evitar problemas con las claves
        $_SESSION['carrito'] = array_values($_SESSION['carrito']);
    }
}

// Mostrar el contenido del carrito
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <h1>Carrito de Compras</h1>

    <?php if (count($_SESSION['carrito']) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio Unitario</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total = 0;
                foreach ($_SESSION['carrito'] as $item): 
                    $subtotal = $item['precioUnitario'] * $item['cantidad'];
                    $total += $subtotal;
                ?>
                <tr>
                    <td><?php echo $item['nombreProducto']; ?></td>
                    <td>$<?php echo number_format($item['precioUnitario'], 2); ?></td>
                    <td>
                        <!-- Formulario para actualizar la cantidad -->
                        <form action="carrito.php" method="post">
                            <input type="number" name="cantidad" value="<?php echo $item['cantidad']; ?>" min="1">
                            <input type="hidden" name="idProducto" value="<?php echo $item['idProducto']; ?>">
                            <button type="submit" name="actualizar">Actualizar</button>
                        </form>
                    </td>
                    <td>$<?php echo number_format($subtotal, 2); ?></td>
                    <td>
                        <!-- Formulario para eliminar un producto -->
                        <form action="carrito.php" method="post">
                            <input type="hidden" name="idProducto" value="<?php echo $item['idProducto']; ?>">
                            <button type="submit" name="eliminar">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <h3>Total: $<?php echo number_format($total, 2); ?></h3>
        <form action="checkout.php" method="post">
            <button type="submit">Proceder al Pago</button>
        </form>
    <?php else: ?>
        <p>Tu carrito está vacío</p>
    <?php endif; ?>
</body>
</html>
