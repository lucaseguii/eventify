<?php

$servidor = "localhost";
$usuario  = "root";
$contrasena = "";     
$basedatos = "eventify";

$conexion = new mysqli($servidor, $usuario, $contrasena, $basedatos);

if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

$conexion->set_charset("utf8mb4");
