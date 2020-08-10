<?php



session_start();
session_destroy();
$_SESSION = [];
header('Location: ' . $router->url('login'));
exit();

?>
<h1>deconencté</h1>