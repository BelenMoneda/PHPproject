<?php
    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        if(isset($_POST['email']) && isset($_POST['contraseña']))
        {
            $emailUsuario = $_POST['email'];
            $contraseñaUsuario = $_POST['contraseña'];

            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "povcamaras";  
        
            $conn = new mysqli($servername, $username, $password, $dbname);

            $sql = "SELECT idUsuario, nombreUsuario, apellidos ,email, direccion, telefono, contrasena, idRol, metodoPago FROM usuarios where email='$emailUsuario'";

            $result = $conn->query($sql);
        

            if ($result->num_rows > 0) {

                $row = $result->fetch_assoc();  
                $idUsuario = $row["idUsuario"];
                $nombreUsuario = $row["nombreUsuario"];
                $apellidos = $row["apellidos"];
                $email = $row["email"];
                $direccion = $row["direccion"];
                $telefono = $row["telefono"];
                $contrasena = $row["contrasena"];
                $idRol = $row["idRol"];
                $metodoPago = $row["metodoPago"];  
                
                if($emailUsuario == $email && $contrasena == $contraseñaUsuario)
                {
                    
                    session_start();
                    $_SESSION['idUsuario'] = $idUsuario;
                    $_SESSION['nombreUsuario'] =$nombreUsuario;
                    $_SESSION['apellidos'] = $apellidos;
                    $_SESSION['email'] = $email;
                    $_SESSION['direccion'] = $direccion;
                    $_SESSION['telefono'] = $telefono;
                    $_SESSION['idRol'] = $idRol;
                    $_SESSION['metodoPago'] = $metodoPago;

                    if ($idRol == 1) {
                        header('Location: ../admin/zona-admin.php');
                    }
                    else {
                        header('Location: index.php');
                    }

                }
                else
                {
                    echo "Usuario o contraseña incorrectos";
                }
            }
            else
            {
                echo "Usuario no encontrado"; 
                echo "<br>";
                echo "Desea registrarse?";
                echo "<a href='registro.php'> Registro</a>";
                
            }   
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

</head>
<body>     
        <h1>Inicio de sesión</h1>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <label for="email">Email:</label>
            <input type="text" name="email" id="email" required>        <br>
            <label for="contraseña">Contraseña:</label>
            <input type="password" name="contraseña" id="contraseña" required>        <br>
            <input type="submit" value="Iniciar sesión">
        </form>
</body>
</html>