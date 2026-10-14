<?php 

session_start();

//control de acceso para cliente
if(!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Cliente'){
    //Si no es cliente, es expulsado al login inmediato
    header('Location: index.html');
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../frontend/css/cliente.css">
</head>
<body>
    <div class="container">
        
        <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></h1>
        <h3>Rol actual: <?php echo $_SESSION['rol']; ?> (Permisos de lectura)</h3>
        <p>Bienvenido a tu panel de cliente</p>
    
        <a href="../../Backend/api/logout.php">Cerrar Sesión de manera segura...</a>
    </div>

</body>
</html>