<?php 

session_start();

//control de acceso para supervisor
if(!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Supervisor'){
    //Si no es supervisor, es expulsado al login inmediato
    header('Location: index.html');
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Supervisor</title>
    <link rel="stylesheet" href="../../frontend/css/supervisor.css">

</head>
<body>
    <div class="container">
        
        <!-- htmlspecialchars previene ataques XSS a renderizar datos al usuario. -->
        <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></h1>
        <h3>Rol actual: <?php echo $_SESSION['rol']; ?> (Permisos de lectura y control)</h3>
        <p>Area exclusiva para la supervision de operaciones del sistema</p>
    
        <a href="../../Backend/api/logout.php">Cerrar Sesión de manera segura...</a>
    </div>

</body>
</html>