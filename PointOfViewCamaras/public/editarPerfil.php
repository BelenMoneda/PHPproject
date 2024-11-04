<?php
include '../includes/funciones/sessionStart.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "povcamaras";  

$conn = new mysqli($servername, $username, $password, $dbname);

$idUsuario = $_SESSION['idUsuario'];
$query = "SELECT * FROM USUARIOS WHERE idUsuario = '$idUsuario'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombreUsuario = $_POST['nombreUsuario'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $contrasena = $_POST['contrasena'];

    $updateQuery = "UPDATE USUARIOS SET nombreUsuario='$nombreUsuario', apellidos='$apellidos', email='$email', direccion='$direccion', telefono='$telefono'";

    if (!empty($contrasena)) {
        $hashedPassword = password_hash($contrasena, PASSWORD_DEFAULT);
        $updateQuery .= ", contrasena='$hashedPassword'";
    }

    $updateQuery .= " WHERE idUsuario=$idUsuario";
    mysqli_query($conn, $updateQuery);

    header('Location: editarPerfil.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="../assets/css/editarPerfil.css"> 
</head>
<body>
    <!-- <h2>Editar Perfil</h2> -->
    <form method="POST" action="editarPerfil.php">
        <label for="nombreUsuario">Nombre:</label>
        <input type="text" name="nombreUsuario" value="<?php echo $user['nombreUsuario']; ?>" required>
        
        <label for="apellidos">Apellidos:</label>
        <input type="text" name="apellidos" value="<?php echo $user['apellidos']; ?>" required>
        
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
        
        <label for="direccion">Dirección:</label>
        <input type="text" name="direccion" value="<?php echo $user['direccion']; ?>">
        
        <label for="telefono">Teléfono:</label>
        <input type="text" name="telefono" value="<?php echo $user['telefono']; ?>" required>
        
        <label for="contrasena">Nueva Contraseña (dejar en blanco para no cambiar):</label>
        <input type="password" name="contrasena">
        
        <button type="submit">Actualizar</button>
    </form>
</body>
</html>
