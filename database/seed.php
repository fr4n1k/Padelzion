<?php
$esCli = PHP_SAPI === "cli";
$ip = $_SERVER["REMOTE_ADDR"] ?? "";
if (!$esCli && !in_array($ip, ["127.0.0.1", "::1"], true)) {
  http_response_code(403);
  exit("Solo se puede correr en local.");
}

$servername = "localhost";
$username = "root";
$password = "";

$sql = file_get_contents(__DIR__ . "/padelzion.sql");
if ($sql === false) {
  exit("No se pudo leer database/padelzion.sql");
}

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
  exit("Error de conexion: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

if (!$conn->multi_query($sql)) {
  exit("Error al ejecutar el seed: " . $conn->error);
}

do {
  if ($resultado = $conn->store_result()) {
    $resultado->free();
  }
} while ($conn->next_result());

if ($conn->errno) {
  exit("Error al ejecutar el seed: " . $conn->error);
}

echo "Base padelzion lista.";
