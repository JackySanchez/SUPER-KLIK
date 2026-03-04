<?php
session_start();
// Limpiamos todas las variables de sesión
$_SESSION = array();
// Destruimos la sesión físicamente
session_destroy();
// Redirigimos inmediatamente al login
header("Location: login.php");
exit;
?>