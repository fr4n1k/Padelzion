<?php
include "includes/sesion.php";
include "config/conexion.php";
include "includes/fechas.php";

$pagina = "complejos";

$canchaId = 0;
if (isset($_GET["cancha"])) {
  $canchaId = $_GET["cancha"];
}

$fecha = "";
if (isset($_GET["fecha"])) {
  $fecha = $_GET["fecha"];
}

$hora = "";
if (isset($_GET["hora"])) {
  $hora = $_GET["hora"];
}

$sql = "SELECT canchas.*, complejos.nombre AS complejo, imagenes.archivo AS foto FROM canchas
        JOIN complejos ON complejos.id = canchas.complejo_id
        JOIN imagenes ON imagenes.complejo_id = complejos.id AND imagenes.portada = 1
        WHERE canchas.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $canchaId);
$stmt->execute();
$cancha = $stmt->get_result()->fetch_assoc();

$fechaValida = $fecha >= date("Y-m-d") && $fecha <= date("Y-m-d", strtotime("+6 day"));

if (!$cancha || !$fechaValida || !in_array($hora, $horarios)) {
  header("Location: complejos.php");
  exit;
}

$urlDetalle = "complejo.php?id=" . $cancha["complejo_id"] . "&cancha=" . $cancha["id"] . "&fecha=" . $fecha;

$sql = "SELECT id FROM reservas WHERE cancha_id = ? AND fecha = ? AND hora = ? AND estado = 'confirmada'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $canchaId, $fecha, $hora);
$stmt->execute();
$ocupado = $stmt->get_result()->num_rows > 0;
$pasado = $fecha == date("Y-m-d") && $hora <= date("H:i");

if ($ocupado || $pasado) {
  header("Location: " . $urlDetalle);
  exit;
}

$precio = $cancha["precio_dia"];
$franja = "franja día";
if ($hora >= "18:00") {
  $precio = $cancha["precio_noche"];
  $franja = "franja noche";
}
$total = $precio * 1.5;
$horaFin = date("H:i", strtotime($hora) + 90 * 60);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["acepto"])) {
  $sql = "INSERT INTO reservas (usuario_id, cancha_id, fecha, hora, total) VALUES (?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("iissi", $_SESSION["usuario_id"], $canchaId, $fecha, $hora, $total);
  $stmt->execute();

  header("Location: reserva-confirmada.php?id=" . $conn->insert_id);
  exit;
}

$fechaTexto = $dias[date("w", strtotime($fecha))] . " " . date("d/m/Y", strtotime($fecha));
$limite = strtotime($fecha . " " . $hora) - 24 * 60 * 60;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Confirmá tu reserva - Padelzion</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body class="fondo-gris">

  <?php include "includes/navbar.php"; ?>

  <main class="contenido">

    <!-- Encabezado -->
    <div class="confirmar-encabezado">
      <h1>Confirmá tu reserva</h1>
      <p>Revisá los datos y confirmá</p>
    </div>

    <div class="confirmar-cuerpo">

      <form class="confirmar-principal" action="confirmar.php?cancha=<?php echo $canchaId; ?>&fecha=<?php echo $fecha; ?>&hora=<?php echo $hora; ?>" method="POST">

        <!-- Paso 1 -->
        <div class="bloque">
          <div class="bloque-top">
            <span class="bloque-numero">1</span>
            <div>
              <h2>Revisá tu reserva</h2>
              <p>Verificá que todo esté correcto antes de confirmar.</p>
            </div>
          </div>

          <div class="revision">
            <div class="revision-fila">
              <span class="revision-label">Cancha y horario</span>
              <span class="revision-valor"><?php echo $cancha["complejo"]; ?> · <?php echo $cancha["nombre"]; ?> · <?php echo $cancha["tipo"]; ?> · <?php echo $fechaTexto; ?> · <?php echo $hora; ?> a <?php echo $horaFin; ?> (90 min)</span>
              <a class="revision-editar" href="<?php echo $urlDetalle; ?>&hora=<?php echo $hora; ?>">Editar</a>
            </div>
            <div class="revision-fila">
              <span class="revision-label">Jugador</span>
              <span class="revision-valor"><?php echo $_SESSION["usuario_nombre"]; ?> · <?php echo $_SESSION["usuario_email"]; ?></span>
            </div>
          </div>
        </div>

        <!-- Paso 2 -->
        <div class="bloque">
          <div class="bloque-top">
            <span class="bloque-numero">2</span>
            <div>
              <h2>Pago</h2>
              <p>No se cobra nada ahora.</p>
            </div>
          </div>

          <div class="pago-complejo">
            <span class="pago-icono"><img src="img/iconos/pago-complejo.svg" alt=""></span>
            <div>
              <p class="pago-titulo">Pagás $<?php echo number_format($total, 0, ",", "."); ?> en el complejo</p>
              <p class="pago-texto">Efectivo, débito o transferencia al llegar. Tu turno queda reservado al confirmar.</p>
            </div>
          </div>

          <label class="politica">
            <input type="checkbox" name="acepto" required>
            Leí y acepto la política de cancelación (sin cargo hasta 24 h antes)
          </label>
        </div>

        <!-- Acciones -->
        <div class="confirmar-acciones">
          <a class="boton-borde boton-volver" href="<?php echo $urlDetalle; ?>&hora=<?php echo $hora; ?>"><img src="img/iconos/volver.svg" alt=""> Volver</a>
          <button type="submit" class="boton boton-confirmar"><img src="img/iconos/check-blanco.svg" alt=""> Confirmar reserva</button>
        </div>
      </form>

      <!-- Resumen -->
      <div class="resumen">
        <img class="resumen-foto" src="img/<?php echo $cancha["foto"]; ?>" alt="<?php echo $cancha["complejo"]; ?>">

        <div class="resumen-cuerpo">
          <div>
            <p class="overline resumen-complejo"><?php echo mb_strtoupper($cancha["complejo"]); ?></p>
            <h3><?php echo $cancha["nombre"]; ?> · <?php echo $cancha["tipo"]; ?></h3>
          </div>

          <div class="resumen-filas">
            <div class="resumen-fila"><span>Fecha</span><strong><?php echo $fechaTexto; ?></strong></div>
            <div class="resumen-fila"><span>Horario</span><strong><?php echo $hora; ?> a <?php echo $horaFin; ?></strong></div>
            <div class="resumen-fila"><span>Duración</span><strong>90 minutos</strong></div>
          </div>

          <div class="resumen-filas resumen-montos">
            <div class="resumen-fila"><span>Turno 90 min · <?php echo $franja; ?></span><strong>$<?php echo number_format($total, 0, ",", "."); ?></strong></div>
          </div>

          <div class="resumen-total">
            <span>Total</span>
            <strong>$<?php echo number_format($total, 0, ",", "."); ?></strong>
          </div>

          <div class="resumen-nota">
            <img src="img/iconos/escudo.svg" alt="">
            <?php if ($limite > time()) { ?>
              <p>Cancelación sin cargo hasta el <?php echo mb_strtolower($dias[date("w", $limite)]); ?> <?php echo date("d/m", $limite); ?> a las <?php echo date("H:i", $limite); ?>.</p>
            <?php } else { ?>
              <p>Faltan menos de 24 h para el turno, así que ya no se puede cancelar sin cargo.</p>
            <?php } ?>
          </div>
        </div>
      </div>

    </div>
  </main>

</body>
</html>
