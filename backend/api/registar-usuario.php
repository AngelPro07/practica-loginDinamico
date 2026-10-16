<?php 
session_start();
if(!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador'){
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "operacion no permitida"]);
    exit;
}

header('Content-Type: application/json');
require '../config/conexion.php';

$entrada = json_decode(file_get_contents('php://input'), true);

$userPost = $entrada['usuario'] ?? '';
$passPost = $entrada['password'] ?? '';
$rolIdPost = $entrada['rol_id'] ?? '';

if(empty($userPost) || empty($passPost) || empty($rolIdPost)){
    echo json_encode(["status" => "error", "message" => "todos los campos son obligatorios"]);
    exit();
}

try {
    $checkStmt = $pdo->prepare("SELECT id FROM usuario WHERE usuario = ?");
    $checkStmt->execute([$userPost]);
    if($checkStmt->fetch()){
        echo json_encode(["status" => "error", "message" => "el nombre de usuario ya esta registrado"]);
        exit;
    }
    /*
        2. CREACION DEL HASH DE CONTRASEÑA SEGURO BCRYPT
        PASSWORD_BCRYPT genera un string seguro de 60 caracteres incorporando una salida unica
    */

    $passwordHash = password_hash($passPost, PASSWORD_BCRYPT);  

    /*
        3. INSERCCION DE LOS DATOS MEDIANTE SENTENCIA PREPARADA
        evitamos inyeccion SQL asegurando que los parametros sean interpretados como datos
    */

    $sql = "INSERT INTO usuario(usuario, password, rol_id) VALUES (?,?,?)";
    $insertStmt = $pdo->prepare("$sql");
    $insertStmt->execute([$userPost, $passwordHash, $rolIdPost]);

    //respuesta exitosa interpretada
    echo json_encode(["status" => "success", "message" => "¡Usuario '{$userPost}'registrado con exito!"]);
    

} catch (\PDOException $e) {
    //si la llave foranea falla (un id de rol alterado) o un error de base de datos ocu
    echo json_encode(["status" => "error", "message" => "Eror interno"]);
    
}

?>