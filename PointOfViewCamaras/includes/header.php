<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rethink+Sans:ital,wght@0,400..800;1,400..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/header.css">
</head>
<body>
    <div id="cabecera">
        <a href="index.php">
            <img id="logo" src="../assets/images/logo/logo.jpg" alt="logo" ></img>
        </a>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="productos.php">Productos</a></li>
                <li><a href="registro.php">Registrarse</a></li>
                <?php if(isset($_SESSION['nombreUsuario'])): ?>
                    <li class="dropdown">
                        <a href="" class="dropbtn"><?php echo $_SESSION['nombreUsuario']; ?></a>
                        <div class="dropdown-content">
                            <?php if($_SESSION['idRol'] != 1): ?>
                                <a href="editarPerfil.php">Editar perfil</a>
                            <?php endif; ?>
                            <a href="misPedidos.php">Mis pedidos</a>
                            <a href="login-destroy.php">Cerrar sesión</a>
                        </div>
                    </li>
                <?php else: ?>
                    <li class="dropdown">
                        <a href=" " class="dropbtn">Perfil</a>
                        <div class="dropdown-content">
                            <a href="login.php">Login</a>
                        </div>
                    </li>
                <?php endif; ?>
                <li><a href="carrito.php">Carrito</a></li>
            </ul>
        </nav> 
    </div>
</body>
</html>
