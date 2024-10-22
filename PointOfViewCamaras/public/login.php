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
    <link rel="stylesheet" href="../assets/css/login.css">

</head>
<body>     

        <!-- <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <label for="email">Email:</label>
            <input type="text" name="email" id="email" required>        <br>
            <label for="contraseña">Contraseña:</label>
            <input type="password" name="contraseña" id="contraseña" required>        <br>
            <input type="submit" value="Iniciar sesión">
        </form>
        <a href="registro.php">Registrarse</a> -->

        <section class="form-main">
        <div class="form-content">
            <div class="box">
                <a href="index.php"><img src="../assets/images/logo/logo.jpg" class="logo"></a>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                    <div class="input-box">
                        <input type="text" placeholder="Email" name="email" class="input-control" required>
                    </div>
                    <div class="input-box">
                        <input type="password" placeholder="Contraseña" name="contraseña" class="input-control" required>
                    </div>
                    <button type="submit" class="btn">Iniciar Sesión</button>
                </form>
                <p>No tienes una cuenta? <a href="registro.php" class="gradient-text">Crear cuenta</a></p>
            </div>
        </div>
        </section>
</body>
</html>