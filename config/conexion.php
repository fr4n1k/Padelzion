<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "padelzion2";

// Crear la conexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexion
if ($conn->connect_error) {
  die("Error de conexion: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
