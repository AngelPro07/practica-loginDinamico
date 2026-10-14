<?php
//Configuración estricta de cookies de sesión para mitigar secuesntros de sesión (Session Hijacking)
session_start([
    'cookie_lifetime' => 0, //Expira a cerrar el navegador
    'cookie_secure' => false, //Cambia a True si usa HTTPS en producción
    'cookie_httponly' => true, //JavaScrip no uede leeer la cookie (Mitiga XSS)
    //'cookie_samesite' => 'Strict' //No se envie la cookie en navegaciones externas
]);

header('Content-Type: application/json');
require '../config/conexion.php';

//Capturar el flujo JSON crudo enviado desde JavaScrip
$entrada = json_decode(file_get_contents('php://input'), true);

$userPost = $entrada['usuario'] ?? '';
$passPost = $entrada['password'] ?? '';

if(empty($userPost) || empty($passPost)){
    echo json_encode(["status" => "error", "message" => "Campos inválidos."]);
    exit;
}

/*
    1. Defensa ANTI-INYECCIÓNSQL: Sentencia Preparada(Prepared Statement). El marcador de posición '?' le dice al motor de la BD que lo que vaya ahí es 
    estrictamente un TEXTO LITERIAL, no código ejecutable. 
*/
$sql = "SELECT u.id, u.password, r.nombre AS rol_nombre FROM usuario u INNER JOIN roles r ON u.rol_id = r.id WHERE u.usuario = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$userPost]);
$usuarioDB = $stmt->fetch();

/*
    2. Comprobación de contraseña con BCRYPT HASH.
    password_verify() extrae automáticamente la sal y el costo del hash guardado en la BD y computa la contraseña ingresada para ver si coinciden.  
*/

if($usuarioDB && password_verify($passPost, $usuarioDB['password'])){
    //Defensa crítica: cambia el ID de la sesión para evitar Session Fixation
    session_regenerate_id(true);
    
    //Guardar información minima y necesaria en la sesión del servidor.
    $_SESSION['id_usuario'] = $usuarioDB['id'];
    $_SESSION['nombre_usuario'] = $userPost;
    $_SESSION['rol'] = $usuarioDB['rol_nombre'];

    //Enrutamiento dinámico segun el rol recuperado de la base de datos
    $redireccion = 'cliente.php';
    if($usuarioDB['rol_nombre'] === 'Administrador') $redireccion = 'admin.php';
    if($usuarioDB['rol_nombre'] === 'Supervisor') $redireccion = 'Supervisor.php';

    echo json_encode(["status" => "success", "redirect" => $redireccion]);
} else {
    //Mensaje genérico: No le digan al atacante si fallo el usuario o la contraseña
    echo json_encode(["status" => "error", "message" => "Credenciales incorrectas."]);
}