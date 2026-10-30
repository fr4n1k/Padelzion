<?php
include "includes/sesion.php";
include "config/conexion.php";
include "includes/fechas.php";

$pagina = "mis-reservas";
$limite = date("Y-m-d H:i", strtotime("+24 hours"));

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $sql = "UPDATE reservas SET estado = 'cancelada'
          WHERE id = ? AND usuario_id = ? AND estado = 'confirmada' AND CONCAT(fecha, ' ', hora) > ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("iis", $_POST["reserva"], $_SESSION["usuario_id"], $limite);
  $stmt->execute();

  if ($stmt->affected_rows > 0) {
    header("Location: mis-reservas.php?cancelada=1");
  } else {
    header("Location: mis-reservas.php");
  }
  exit;
}

$sql = "SELECT reservas.*, canchas.nombre AS cancha, canchas.tipo, complejos.nombre AS complejo, imagenes.archivo AS foto FROM reservas
        JOIN canchas ON canchas.id = reservas.cancha_id
        JOIN complejos ON complejos.id = canchas.complejo_id
        JOIN imagenes ON imagenes.complejo_id = complejos.id AND imagenes.portada = 1
        WHERE reservas.usuario_id = ?
        ORDER BY reservas.fecha, reservas.hora";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION["usuario_id"]);
$stmt->execute();
$resultado = $stmt->get_result();

$proximas = [];
$pasadas = [];
$canceladas = [];
while ($reserva = $resultado->fetch_assoc()) {
  if ($reserva["estado"] == "cancelada") {
    $canceladas[] = $reserva;
  } else if ($reserva["fecha"] . " " . $reserva["hora"] < date("Y-m-d H:i")) {
    $pasadas[] = $reserva;
  } else {
    $proximas[] = $reserva;
  }
}

$tab = "proximas";
if (isset($_GET["tab"])) {
  $tab = $_GET["tab"];
}

if ($tab == "pasadas") {
  $lista = $pasadas;
  $vacio = "No tenés reservas pasadas";
} else if ($tab == "canceladas") {
  $lista = $canceladas;
  $vacio = "No tenés reservas canceladas";
} else {
  $tab = "proximas";
  $lista = $proximas;
  $vacio = "Todavía no tenés reservas";
}

$aCancelar = null;
if (isset($_GET["cancelar"])) {
  foreach ($proximas as $reserva) {
    if ($reserva["id"] == $_GET["cancelar"] && $reserva["fecha"] . " " . $reserva["hora"] > $limite) {
      $aCancelar = $reserva;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mis reservas - Padelzion</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body class="fondo-gris">

  <?php include "includes/navbar.php"; ?>

  <main class="contenido mis-reservas">

    <!-- Encabezado -->
    <div class="mis-reservas-encabezado">
      <div class="encabezado-pagina">
        <div class="breadcrumb">
          <a href="index.php">Inicio</a>
          <img src="img/iconos/flecha-derecha.svg" alt="">
          <span>Mis reservas</span>
        </div>
        <h1>Mis reservas</h1>
      </div>
      <a class="boton boton-nueva" href="complejos.php"><img src="img/iconos/reservar.svg" alt=""> Nueva reserva</a>
    </div>

    <!-- Pestañas -->
    <div class="tabs">
      <a href="mis-reservas.php?tab=proximas" class="<?php if ($tab == "proximas") echo "activo"; ?>">Próximas <span><?php echo count($proximas); ?></span></a>
      <a href="mis-reservas.php?tab=pasadas" class="<?php if ($tab == "pasadas") echo "activo"; ?>">Pasadas <span><?php echo count($pasadas); ?></span></a>
      <a href="mis-reservas.php?tab=canceladas" class="<?php if ($tab == "canceladas") echo "activo"; ?>">Canceladas <span><?php echo count($canceladas); ?></span></a>
    </div>

    <?php if (isset($_GET["cancelada"])) { ?>
      <div class="aviso aviso-exito">
        <img src="img/iconos/check-verde-oscuro.svg" alt="">
        <p>La reserva fue cancelada y el horario quedó libre para otros jugadores.</p>
      </div>
    <?php } ?>

    <!-- Lista -->
    <?php if (count($lista) == 0) { ?>
      <div class="sin-reservas">
        <span class="sin-reservas-icono"><img src="img/iconos/sin-reservas.svg" alt=""></span>
        <div>
          <h2><?php echo $vacio; ?></h2>
          <p>Cuando reserves una cancha, vas a ver acá tus próximos turnos y el historial.</p>
        </div>
        <a class="boton boton-nueva" href="complejos.php"><img src="img/iconos/reservar.svg" alt=""> Reservar cancha</a>
      </div>
    <?php } else { ?>
      <div class="lista-reservas">
        <?php foreach ($lista as $reserva) { ?>
          <?php include "includes/card-reserva.php"; ?>
        <?php } ?>
      </div>
    <?php } ?>

    <div class="aviso aviso-info">
      <img src="img/iconos/info-azul.svg" alt="">
      <p>Podés cancelar sin cargo hasta 24 horas antes del turno. Tus reservas anteriores están en la pestaña “Pasadas”.</p>
    </div>

  </main>

  <?php include "includes/footer.php"; ?>

  <!-- Modal cancelar -->
  <?php if ($aCancelar) { ?>
    <?php
    $fechaModal = strtotime($aCancelar["fecha"]);
    $codigoModal = "PZ-" . date("y", strtotime($aCancelar["creada"])) . "-" . str_pad($aCancelar["id"], 5, "0", STR_PAD_LEFT);
    ?>
    <div class="modal-fondo">
      <form class="modal" action="mis-reservas.php" method="POST">
        <input type="hidden" name="reserva" value="<?php echo $aCancelar["id"]; ?>">

        <div class="modal-top">
          <span class="modal-icono"><img src="img/iconos/alerta.svg" alt=""></span>
          <div>
            <h2>¿Cancelar la reserva?</h2>
            <p>Vas a cancelar la reserva <?php echo $codigoModal; ?> de <?php echo $aCancelar["cancha"]; ?> · <?php echo $aCancelar["tipo"]; ?> (<?php echo $aCancelar["complejo"]; ?>) del <?php echo mb_strtolower($dias[date("w", $fechaModal)]); ?> <?php echo date("j", $fechaModal); ?> de <?php echo $meses[date("n", $fechaModal) - 1]; ?> a las <?php echo $aCancelar["hora"]; ?>. Como faltan más de 24 horas, no tiene ningún cargo y el horario se libera para otros jugadores.</p>
          </div>
          <a class="modal-cerrar" href="mis-reservas.php"><img src="img/iconos/cerrar.svg" alt="Cerrar"></a>
        </div>

        <div class="modal-acciones">
          <a class="boton-borde" href="mis-reservas.php">Volver</a>
          <button type="submit" class="boton-peligro">Sí, cancelar reserva</button>
        </div>
      </form>
    </div>
  <?php } ?>

</body>
</html>
