<?php
session_start();
include "config/conexion.php";

if (isset($_SESSION["usuario_id"])) {
  header("Location: index.php");
  exit;
}

$error = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $password = $_POST["password"];

  $sql = "SELECT id, nombre, apellido, email FROM usuarios WHERE email = ? AND password = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $email, $password);
  $stmt->execute();
  $resultado = $stmt->get_result();
  $usuario = $resultado->fetch_assoc();

  if ($usuario) {
    $_SESSION["usuario_id"] = $usuario["id"];
    $_SESSION["usuario_nombre"] = $usuario["nombre"] . " " . $usuario["apellido"];
    $_SESSION["usuario_iniciales"] = mb_substr($usuario["nombre"], 0, 1) . mb_substr($usuario["apellido"], 0, 1);
    $_SESSION["usuario_email"] = $usuario["email"];
    header("Location: index.php");
    exit;
  } else {
    $error = "El email o la contraseña no son correctos.";
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ingresar - Padelzion</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

  <div class="login">

    <!-- Panel con la foto -->
    <div class="login-panel">
      <div class="logo">
        <img src="img/iconos/logo.png" alt="Padelzion">
        <span>Padelzion</span>
      </div>

      <div class="login-mensaje">
        <h2>Más tiempo jugando, menos tiempo coordinando.</h2>
        <ul>
          <li><img src="img/iconos/check-verde.svg" alt=""> Turnos en tiempo real las 24 horas</li>
          <li><img src="img/iconos/check-verde.svg" alt=""> Pagás directo en el complejo</li>
          <li><img src="img/iconos/check-verde.svg" alt=""> Cancelación sin cargo hasta 24 hs antes</li>
        </ul>
      </div>
    </div>

    <!-- Formulario -->
    <div class="login-area">
      <form class="login-form" action="login.php" method="POST">
        <div>
          <h1>Ingresá a tu cuenta</h1>
          <p class="bajada">Reservá y gestioná tus turnos desde un solo lugar.</p>
        </div>

        <div class="aviso aviso-info">
          <img src="img/iconos/info-azul.svg" alt="">
          <p>Versión demo: ingresá con la cuenta franco.bollati@demo.com, ya cargada en el sistema.</p>
        </div>

        <?php if ($error != "") { ?>
          <div class="aviso aviso-error">
            <p><?php echo $error; ?></p>
          </div>
        <?php } ?>

        <div class="login-campos">
          <div class="campo">
            <label for="email">Email</label>
            <div class="campo-input">
              <img src="img/iconos/mail.svg" alt="">
              <input type="email" id="email" name="email" placeholder="tu@email.com" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
          </div>

          <div class="campo">
            <label for="password">Contraseña</label>
            <div class="campo-input">
              <img src="img/iconos/candado.svg" alt="">
              <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>
          </div>
        </div>

        <button type="submit" class="boton">Ingresar</button>
      </form>
    </div>

  </div>

</body>
</html>
