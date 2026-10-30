<?php
include "includes/sesion.php";
include "config/conexion.php";
include "includes/fechas.php";

$pagina = "mis-reservas";

$id = 0;
if (isset($_GET["id"])) {
  $id = $_GET["id"];
}

$sql = "SELECT reservas.*, canchas.nombre AS cancha, canchas.tipo, complejos.nombre AS complejo FROM reservas
        JOIN canchas ON canchas.id = reservas.cancha_id
        JOIN complejos ON complejos.id = canchas.complejo_id
        WHERE reservas.id = ? AND reservas.usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $_SESSION["usuario_id"]);
$stmt->execute();
$reserva = $stmt->get_result()->fetch_assoc();

if (!$reserva) {
  header("Location: index.php");
  exit;
}

$fecha = strtotime($reserva["fecha"]);
$fechaTexto = $dias[date("w", $fecha)] . " " . date("j", $fecha) . " de " . $meses[date("n", $fecha) - 1] . " de " . date("Y", $fecha);
$horaFin = date("H:i", strtotime($reserva["hora"]) + 90 * 60);
$codigo = "PZ-" . date("y", strtotime($reserva["creada"])) . "-" . str_pad($reserva["id"], 5, "0", STR_PAD_LEFT);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reserva confirmada - Padelzion</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body class="fondo-gris">

  <?php include "includes/navbar.php"; ?>

  <main class="confirmada">
    <div class="confirmada-card">

      <!-- Mensaje -->
      <div class="confirmada-top">
        <span class="confirmada-icono"><img src="img/iconos/check-blanco.svg" alt=""></span>
        <h1>¡Reserva confirmada!</h1>
        <p>Guardá tu código de reserva. ¡Nos vemos en la cancha!</p>
        <div class="codigo">
          <div>
            <p class="codigo-label">CÓDIGO DE RESERVA</p>
            <p class="codigo-valor"><?php echo $codigo; ?></p>
          </div>
          <button type="button" class="boton-copiar" title="Copiar código" onclick="navigator.clipboard.writeText('<?php echo $codigo; ?>'); this.querySelector('img').src = 'img/iconos/copiado.svg';">
            <img src="img/iconos/copiar.svg" alt="Copiar">
          </button>
        </div>
      </div>

      <!-- Detalle -->
      <div class="confirmada-detalle">
        <div class="resumen-fila"><span>Cancha</span><strong><?php echo $reserva["complejo"]; ?> · <?php echo $reserva["cancha"]; ?> · <?php echo $reserva["tipo"]; ?></strong></div>
        <div class="resumen-fila"><span>Fecha</span><strong><?php echo $fechaTexto; ?></strong></div>
        <div class="resumen-fila"><span>Horario</span><strong><?php echo $reserva["hora"]; ?> a <?php echo $horaFin; ?> (90 minutos)</strong></div>
        <div class="resumen-fila"><span>Titular</span><strong><?php echo $_SESSION["usuario_nombre"]; ?></strong></div>
        <div class="resumen-fila"><span>Pago</span><strong>En el complejo, al llegar</strong></div>
        <div class="resumen-total">
          <span>Total a pagar</span>
          <strong>$<?php echo number_format($reserva["total"], 0, ",", "."); ?></strong>
        </div>
      </div>

      <!-- Acciones -->
      <div class="confirmada-acciones">
        <a class="boton-borde" href="index.php">Volver al inicio</a>
        <a class="boton" href="mis-reservas.php"><img src="img/iconos/ticket.svg" alt=""> Ver mis reservas</a>
      </div>

    </div>
  </main>

</body>
</html>
