<?php
//Destruccion de la sesion 

session_start();
$_SESSION = array(); // limpiar las variables en memoria

//Borrar la cookie fisica 
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 4200, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);

}

session_destroy();
header("Location: ../../frontend/views/index.html");
exit;