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

$idPedido = $_POST['idPedido']; // El ID del pedido que se pasó desde el carrito
$idUsuario = $_SESSION['idUsuario'];
$nombreUsuarioRegistrado = $_SESSION['nombreUsuario'];
$apellidosRegistrados = $_SESSION['apellidos'];
$emailRegistrado = $_SESSION['email'];
$direccionRegistrada = $_SESSION['direccion'];



// Verificar si se ha enviado el formulario de completar pedido
if (isset($_POST['completarPedido'])) {
    $nombreUsuario = $_POST['nombreUsuario'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $direccion = $_POST['direccion'];
    $fechaPedido = date("Y-m-d"); // Fecha actual
    $estadoPedido = "procesado"; // Estado del pedido después de ser completado
    $estadoPago = "Pendiente"; // Por defecto, el pago está pendiente

    // Validar los campos requeridos
    if (empty($nombreUsuario) || empty($apellidos) || empty($email)) {
        $error = "Por favor, completa todos los campos requeridos.";
    } else {
        
        $sqlUpdatePedido = "UPDATE PEDIDO 
                            SET nombreUsuario='$nombreUsuario', apellidos='$apellidos', email='$email', direccion='$direccion', 
                                estadoPedido='$estadoPedido', fechaPedido='$fechaPedido', estadoPago='$estadoPago' 
                            WHERE idPedido='$idPedido'";

        if ($conn->query($sqlUpdatePedido) === TRUE) {
            $success = "Datos completados exitosamente. ";
            header("Location: pago.php?idPedido=$idPedido");
        } else {
            $error = "Error al completar el pedido: " . $conn->error;
        }
    }
}


$sqlPedido = "SELECT precioTotal FROM PEDIDO WHERE idPedido='$idPedido' AND idUsuario='$idUsuario'";
$resultPedido = $conn->query($sqlPedido);

if ($resultPedido->num_rows > 0) {
    $rowPedido = $resultPedido->fetch_assoc();
    $precioTotal = $rowPedido['precioTotal'];
} else {
    die("No se encontró el pedido.");
}


$sqlProductos = "SELECT LP.idProducto, P.nombreProducto, LP.cantidad, LP.precioUnitario, LP.subtotal 
                 FROM LINEA_PEDIDO LP 
                 JOIN PRODUCTO P ON LP.idProducto = P.idProducto 
                 WHERE LP.idPedido='$idPedido'";
$resultProductos = $conn->query($sqlProductos);

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completar Pedido</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .form-container { max-width: 500px; margin: auto; }
        .form-container h2 { text-align: center; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 8px; }
        .error { color: red; margin-bottom: 15px; }
        .success { color: green; margin-bottom: 15px; }
        .total-container { font-weight: bold; text-align: center; margin-top: 20px; }
        .productos-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .productos-table th, .productos-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .productos-table th { background-color: #f2f2f2; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Completar Pedido</h2>

    <?php if (isset($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <p class="success"><?php echo $success; ?></p>
    <?php endif; ?>

    <!-- Mostrar los productos en el pedido -->
    <h3>Productos en el Pedido:</h3>
    <table class="productos-table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultProductos && $resultProductos->num_rows > 0): ?>
                <?php while ($rowProducto = $resultProductos->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $rowProducto['nombreProducto']; ?></td>
                        <td><?php echo $rowProducto['cantidad']; ?></td>
                        <td>$<?php echo number_format($rowProducto['precioUnitario'], 2); ?></td>
                        <td>$<?php echo number_format($rowProducto['subtotal'], 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No hay productos en este pedido.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="total-container">
        <p>Total del Pedido: $<?php echo number_format($precioTotal, 2); ?></p>
    </div>

    <form action="pedido.php" method="post">
        <input type="hidden" name="idPedido" value="<?php echo $idPedido; ?>">

        <div class="form-group">
            <label for="nombreUsuario">Nombre</label>
            <input type="text" name="nombreUsuario" id="nombreUsuario"  value="<?php echo "$nombreUsuarioRegistrado"?>" required>
        </div>

        <div class="form-group">
            <label for="apellidos">Apellidos</label>
            <input type="text" name="apellidos" id="apellidos" value="<?php echo "$apellidosRegistrados"?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?php echo"$emailRegistrado"?>" required>
        </div>

        <div class="form-group">
            <label for="direccion">Dirección</label>
            <input type="text" name="direccion" id="direccion" value="<?php echo"$direccionRegistrada"?>" required>
        </div>

        <button type="submit" name="completarPedido">Completar Pedido</button>
    </form>
</div>

</body>
</html>
