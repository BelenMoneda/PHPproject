<?php
include '../includes/funciones/sessionStart.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "povcamaras";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}



$idUsuario = $_SESSION['idUsuario'];

$query = "SELECT * FROM PEDIDO WHERE idUsuario = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Pedidos</title>
    <link rel="stylesheet" href="../assets/css/misPedidos.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <div class="container">
        <h1>Mis Pedidos</h1>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Identificador de pedido</th>
                        <th>Fecha de solicitud</th>
                        <th>Estado </th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['idPedido']; ?></td>
                            <td><?php echo $row['fechaPedido']; ?></td>
                            <td><?php echo $row['estadoPedido']; ?></td>
                            <td><?php echo $row['precioTotal']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No has realizado ningún pedido.</p>
        <?php endif; ?>
    </div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
