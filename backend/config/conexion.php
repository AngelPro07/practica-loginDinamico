<?php 

//configurar la conexion de a BD con las credenciales
$host = '127.0.0.1';
$db = 'sistema_roles_dinamico';
$user = 'root';
$pass = 'root';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

//opciones de seguridad de PDO
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //Reportar errores como excepciones editables
    PDO::ATTR_DEFAULT_FETCH_MODE =>PDO::FETCH_ASSOC, //Retotnar arreglos asociativos
    PDO::ATTR_EMULATE_PREPARES => false, //desactivar emulacion para mitigar sql injection raiz
];

//header ('Content-Type: application/json');
try{
    $pdo = new PDO ($dsn, $user, $pass, $options);
    /*echo json_encode([
        "status" => "success",
        "message" => "¡Conexion establecida"
    ]);*/
}catch(\PDOException $e ){
    //no mostrar $e->getMesseg() en produccion, expone credenciales
    echo json_encode(["Status"=> "error","message" => "Error interno del servidor."]);
}


?>