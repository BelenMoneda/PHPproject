<?php
    if(isset($_POST["idProducto"]) && isset($_POST["stock"]))
    {
        $productoConsultado = $_POST["idProducto"];
        $stock = $_POST["stock"];

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "povcamaras";  
        
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $email = $_POST['email'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $contraseña = $_POST['contraseña'];

        $sql = "INSERT INTO linea_pedido ('', cantidad , precioUnitario,'','idProducto', 'subtotal') VALUES ('', '', '$precioUnitario', '$productoConsultado', '$productoConsultado', '$subtotal')";
    
        if ($conn->query($sql) === TRUE) {
            header("Location: login.php");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
?>

<!-- REATE TABLE LINEA_PEDIDO(
    idLineaPedido INT AUTO_INCREMENT,
    cantidad INT NOT NULL,
    precioUnitario DECIMAL(10,2) NOT NULL,
    idPedido INT,
    idProducto INT,
    subtotal DECIMAL(10,2) NOT NULL,
    CONSTRAINT PK_LINEA_PEDIDO PRIMARY KEY (idLineaPedido),
    CONSTRAINT FK_LINEA_PEDIDO_PEDIDO FOREIGN KEY (idPedido) REFERENCES PEDIDO(idPedido),
    CONSTRAINT FK_LINEA_PEDIDO_PRODUCTO FOREIGN KEY (idProducto) REFERENCES PRODUCTO(idProducto)
); -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>     
        <h1>Carrito</h1>   
    
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
            <label for="idProducto">ID Producto:</label>
            <input type="text" name="idProducto" id="idProducto" required>        <br>
            <label for="stock">Stock:</label>
            <input type="text" name="stock" id="stock" required>        <br>
            <input type="submit" value="Comprar">
        </form>
</body>
</html>