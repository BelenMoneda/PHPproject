<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['idUsuario'])) {
    header("Location: login.php");
    exit();
}

// Verificar si se ha pasado un idPedido
if (!isset($_GET['idPedido'])) {
    echo "No se ha seleccionado ningún pedido.";
    exit();
}

$idPedido = $_GET['idPedido'];

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "povcamaras";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los métodos de pago disponibles
$sqlMetodosPago = "SELECT idMetodoPago, nombreMetodoPago FROM MetodoPago";
$resultMetodosPago = $conn->query($sqlMetodosPago);

// Procesar el pago cuando se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idMetodoPago = $_POST['metodoPago'];
    $monto = $_POST['monto'];  // El monto total a pagar se debe calcular en base al pedido
    $estadoPago = 'Completado';  // Se puede cambiar el estado según el proceso de pago
    $fechaPago = date('Y-m-d');

    // Insertar en la tabla Pago
    $sqlPago = "INSERT INTO Pago (idPedido, idMetodoPago, monto, estadoPago, fechaPago) 
                VALUES (?, ?, ?, ?, ?)";
    $stmtPago = $conn->prepare($sqlPago);
    $stmtPago->bind_param("iidss", $idPedido, $idMetodoPago, $monto, $estadoPago, $fechaPago);
    $stmtPago->execute();
    $stmtPago->close();

    // Actualizar el estado del pedido a "Pagado"
    $sqlUpdatePedido = "UPDATE PEDIDO SET estadoPedido = 'Pagado' WHERE idPedido = ?";
    $stmtUpdatePedido = $conn->prepare($sqlUpdatePedido);
    $stmtUpdatePedido->bind_param("i", $idPedido);
    $stmtUpdatePedido->execute();
    $stmtUpdatePedido->close();

    echo "Pago procesado exitosamente. Gracias por su compra.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago del Pedido</title>
</head>
<body>
    <h1>Pago del Pedido</h1>

    <form method="POST" action="">
        <label for="metodoPago">Selecciona el método de pago:</label>
        <select name="metodoPago" id="metodoPago" required>
            <?php while ($row = $resultMetodosPago->fetch_assoc()): ?>
                <option value="<?php echo $row['idMetodoPago']; ?>">
                    <?php echo htmlspecialchars($row['nombreMetodoPago']); ?>
                </option>
            <?php endwhile;
        
