<?php
session_start();

if (!isset($_SESSION["usuario_id"])) {
  header("Location: login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Padelzion</title>
</head>
<body>
  <p>Hola, <?php echo $_SESSION["usuario_nombre"]; ?>. <a href="logout.php">Cerrar sesión</a></p>
</body>
</html>
