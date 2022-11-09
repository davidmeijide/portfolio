<?php
//Datos da configuración da base de datos
$servidor='db-pdo';
$usuario='tarefa';
$pass='Tarefa4.7';
$base='tarefacopy';

$pdo = new PDO("mysql:host=$servidor;dbname=$base;charset=utf8mb4", $usuario, $pass);
?>
