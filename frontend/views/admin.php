<?php
session_start();

//Control de acceso de servidor.
if(!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador'){
    //Si no está logueado o no pertenece aquí, es expulsado al login inmediato
    header('Location: index.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <!-- htmlspecialchars previene ataques XSS a renderizar datos al usuario. -->
    <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></h1>
    <h3>Rol actual: <?php echo $_SESSION['nombre_usuario']; ?> (Acceso Total)</h3>
    <p>Area exclusiva de administración</p>

    <a href="../../backend/api/logout.php">Cerrar Sesión de manera segura...</a>
</body>
</html>