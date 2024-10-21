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
if(isset($_POST['realizarPedido'])) {
    $idPedido = $_POST['idPedido'];

    // Actualizamos el estado del pedido a "pagado"
    $sqlUpdatePedido = "UPDATE PEDIDO SET estadoPedido='finalizado', estadoPago='Pagado' ,fechaPedido=NOW() WHERE idPedido='$idPedido'";
    
    if ($conn->query($sqlUpdatePedido) === TRUE) {
        echo "<h2>Pedido realizado con éxito. ¡Gracias por tu compra!</h2>";
        echo "<p><a href='index.php'>Volver al inicio</a></p>";
    } else {
        echo "Error al actualizar el estado del pedido: " . $conn->error;
    }
}

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
    </style>
</head>
<body>
</body>
</html>




