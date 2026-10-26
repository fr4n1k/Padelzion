<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "padelzion";

// Crear la conexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexion
if ($conn->connect_error) {
  die("Error de conexion: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

date_default_timezone_set("America/Argentina/Mendoza");
?>
