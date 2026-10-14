<?php
require_once __DIR__ . '/../config/conexion.php';

try {
    // Generamos los hashes utilizando el motor PHP de tu propio Laragon
    $hashAdmin = password_hash('admin123', PASSWORD_BCRYPT);
    $hashSuper = password_hash('super123', PASSWORD_BCRYPT);
    $hashCliente = password_hash('cliente123', PASSWORD_BCRYPT);

    // Actualizamos la base de datos con los hashes nativos recién creados
    $sql = "UPDATE usuario SET password = ? WHERE usuario = ?";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([$hashAdmin, 'admin_user']);
    $stmt->execute([$hashSuper, 'super_user']);
    $stmt->execute([$hashCliente, 'cliente_user']);

    echo "<h3>¡Hashes nativos actualizados con éxito en tu base de datos!</h3>";
    echo "Admin Hash: " . $hashAdmin . "<br>";
    echo "Puedes borrar este archivo 'fix.php' por seguridad.";

} catch (Exception $e) {
    echo "Error al actualizar: " . $e->getMessage();
}