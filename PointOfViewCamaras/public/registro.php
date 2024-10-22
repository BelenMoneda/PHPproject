<?php
    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        if(isset($_POST['nombre']) && isset($_POST['apellidos']) && isset($_POST['email']) && isset($_POST['direccion']) && isset($_POST['telefono']) && isset($_POST['contraseña']))
        {

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

            $sql = "INSERT INTO usuarios (nombreUsuario, apellidos, email,direccion,telefono, contrasena,idRol) VALUES ('$nombre','$apellidos', '$email','$direccion','$telefono', '$contraseña','2')";
        
            if ($conn->query($sql) === TRUE) {
                echo "Usuario creado correctamente";
                sleep(2);
                header("Location: login.php");
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
        else {
            echo "Faltan datos";
        }

    }
?>
            
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <h1>Crear nuevo usuario</h1>   

    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required>        <br>
        <label for="apellidos">Apellidos:</label>
        <input type="text" name="apellidos" id="apellidos" required>        <br>
        <label for="email">Email:</label>
        <input type="text" name="email" id="email" required>        <br>
        <label for="direccion">Direccion:</label>
        <input type="text" name="direccion" id="direccion" required>        <br>
        <label for="telefono">Telefono:</label>
        <input type="text" name="telefono" id="telefono" pattern="\d{9}" title="Debe contener 9 números" required>        <br>
        <label for="contraseña">Contraseña:</label>
        <input type="password" name="contraseña" id="contraseña" required>        <br>
        <input type="submit" value="Registrar">
    </form>
</body>
</html>