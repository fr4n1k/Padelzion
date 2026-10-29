<?php
include "includes/sesion.php";
include "config/conexion.php";
include "includes/fechas.php";

$pagina = "complejos";
$iconosTipo = ["Panorámica" => "panoramica.svg", "Muro" => "muro.svg", "Normal" => "normal.svg"];

function estaLibre($canchaId, $hora, $fecha, $ocupados) {
  if ($fecha == date("Y-m-d") && $hora <= date("H:i")) {
    return false;
  }
  return !in_array($canchaId . " " . $hora, $ocupados);
}

$id = 0;
if (isset($_GET["id"])) {
  $id = $_GET["id"];
}

$sql = "SELECT * FROM complejos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$complejo = $stmt->get_result()->fetch_assoc();

if (!$complejo) {
  header("Location: complejos.php");
  exit;
}

$sql = "SELECT archivo FROM imagenes WHERE complejo_id = ? ORDER BY portada DESC, id";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$imagenes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$sql = "SELECT * FROM canchas WHERE complejo_id = ? ORDER BY id";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$canchas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$cancha = $canchas[0];
if (isset($_GET["cancha"])) {
  foreach ($canchas as $c) {
    if ($c["id"] == $_GET["cancha"]) {
      $cancha = $c;
    }
  }
}

$fechas = [];
for ($i = 0; $i < 7; $i++) {
  $fechas[] = date("Y-m-d", strtotime("+$i day"));
}

$fecha = $fechas[0];
if (isset($_GET["fecha"]) && in_array($_GET["fecha"], $fechas)) {
  $fecha = $_GET["fecha"];
}

$sql = "SELECT reservas.cancha_id, reservas.hora FROM reservas
        JOIN canchas ON canchas.id = reservas.cancha_id
        WHERE canchas.complejo_id = ? AND reservas.fecha = ? AND reservas.estado = 'confirmada'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $id, $fecha);
$stmt->execute();
$resultado = $stmt->get_result();

$ocupados = [];
while ($fila = $resultado->fetch_assoc()) {
  $ocupados[] = $fila["cancha_id"] . " " . $fila["hora"];
}

$hora = "";
if (isset($_GET["hora"]) && in_array($_GET["hora"], $horarios) && estaLibre($cancha["id"], $_GET["hora"], $fecha, $ocupados)) {
  $hora = $_GET["hora"];
}

$libresCancha = 0;
foreach ($horarios as $h) {
  if (estaLibre($cancha["id"], $h, $fecha, $ocupados)) {
    $libresCancha++;
  }
}

$precio = $cancha["precio_dia"];
$franja = "franja día";
if ($hora >= "18:00") {
  $precio = $cancha["precio_noche"];
  $franja = "franja noche";
}
$total = $precio * 1.5;
$horaFin = date("H:i", strtotime($hora) + 90 * 60);

$diaSemana = $dias[date("w", strtotime($fecha))];
$fechaCorta = date("d/m", strtotime($fecha));
$url = "complejo.php?id=" . $complejo["id"] . "&fecha=" . $fecha;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?php echo $complejo["nombre"]; ?> - Padelzion</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body class="fondo-gris">

  <?php include "includes/navbar.php"; ?>

  <main class="contenido contenido-detalle">

    <!-- Encabezado -->
    <div class="encabezado-pagina">
      <div class="breadcrumb">
        <a href="index.php">Inicio</a>
        <img src="img/iconos/flecha-derecha.svg" alt="">
        <a href="complejos.php">Complejos</a>
        <img src="img/iconos/flecha-derecha.svg" alt="">
        <span><?php echo $complejo["nombre"]; ?></span>
      </div>
      <h1><?php echo $complejo["nombre"]; ?></h1>
      <div class="detalle-meta">
        <span class="tag"><img src="img/iconos/canchas-oscuro.svg" alt=""> <?php echo $complejo["cantidad_canchas"]; ?> canchas de pádel</span>
        <span class="detalle-rating">
          <img src="img/iconos/estrella.svg" alt="">
          <strong><?php echo number_format($complejo["puntaje"], 1, ",", "."); ?></strong>
          (<?php echo $complejo["opiniones"]; ?> reseñas)
        </span>
        <span class="detalle-ubicacion"><img src="img/iconos/ubicacion-gris.svg" alt=""> <?php echo $complejo["direccion"]; ?></span>
      </div>
    </div>

    <!-- Galeria -->
    <div class="galeria">
      <img class="galeria-principal" src="img/<?php echo $imagenes[0]["archivo"]; ?>" alt="<?php echo $complejo["nombre"]; ?>">
      <div class="galeria-secundarias">
        <?php foreach (array_slice($imagenes, 1, 2) as $imagen) { ?>
          <img src="img/<?php echo $imagen["archivo"]; ?>" alt="">
        <?php } ?>
      </div>
    </div>

    <div class="detalle-cuerpo">

      <div class="detalle-info">

        <!-- Descripcion -->
        <div>
          <h2>Sobre el complejo</h2>
          <p class="detalle-descripcion"><?php echo $complejo["descripcion"]; ?></p>
        </div>

        <!-- Canchas -->
        <div>
          <h2>Canchas del complejo</h2>
          <p class="detalle-subtitulo">Disponibilidad para el <?php echo mb_strtolower($diaSemana); ?> <?php echo $fechaCorta; ?> · turnos de 90 min</p>

          <div class="lista-canchas">
            <?php foreach ($canchas as $c) { ?>
              <?php
              $libres = 0;
              foreach ($horarios as $h) {
                if (estaLibre($c["id"], $h, $fecha, $ocupados)) {
                  $libres++;
                }
              }
              ?>
              <div class="fila-cancha <?php if ($c["id"] == $cancha["id"]) echo "fila-seleccionada"; ?>">
                <img class="fila-foto" src="img/<?php echo $c["imagen"]; ?>" alt="">

                <div class="fila-textos">
                  <div class="fila-titulo">
                    <strong><?php echo $c["nombre"]; ?></strong>
                    <span class="tag"><img src="img/iconos/<?php echo $iconosTipo[$c["tipo"]]; ?>" alt=""> <?php echo $c["tipo"]; ?></span>
                  </div>
                  <p><?php echo $c["cobertura"]; ?> · $<?php echo number_format($c["precio_dia"], 0, ",", "."); ?> a $<?php echo number_format($c["precio_noche"], 0, ",", "."); ?> / hora</p>
                </div>

                <?php if ($libres == 1) { ?>
                  <span class="fila-disp"><span class="punto punto-verde"></span> 1 turno libre</span>
                <?php } else if ($libres > 1) { ?>
                  <span class="fila-disp"><span class="punto punto-verde"></span> <?php echo $libres; ?> turnos libres</span>
                <?php } else { ?>
                  <span class="fila-disp fila-completa"><span class="punto punto-gris"></span> Completa</span>
                <?php } ?>

                <?php if ($c["id"] == $cancha["id"]) { ?>
                  <span class="badge-seleccionada"><span class="punto punto-verde"></span> Seleccionada</span>
                <?php } else if ($libres > 0) { ?>
                  <a class="boton-elegir" href="<?php echo $url; ?>&cancha=<?php echo $c["id"]; ?>">Elegir</a>
                <?php } else { ?>
                  <span class="boton-elegir boton-completa">Completa</span>
                <?php } ?>
              </div>
            <?php } ?>
          </div>
        </div>

        <!-- Politica -->
        <div class="aviso aviso-info aviso-politica">
          <img src="img/iconos/info-azul.svg" alt="">
          <p>Cancelación sin cargo desde “Mis reservas” hasta 24 horas antes del turno. El pago se hace en el complejo.</p>
        </div>
      </div>

      <!-- Panel de reserva -->
      <div class="panel-reserva">
        <p class="panel-precio">$<?php echo number_format($precio, 0, ",", "."); ?> <span>/ hora · <?php echo $franja; ?></span></p>

        <form class="panel-form" action="complejo.php" method="GET">
          <input type="hidden" name="id" value="<?php echo $complejo["id"]; ?>">

          <div class="campo">
            <label for="cancha">Cancha</label>
            <div class="campo-input">
              <img src="img/iconos/canchas.svg" alt="">
              <select id="cancha" name="cancha">
                <?php foreach ($canchas as $c) { ?>
                  <option value="<?php echo $c["id"]; ?>" <?php if ($c["id"] == $cancha["id"]) echo "selected"; ?>><?php echo $c["nombre"]; ?> · <?php echo $c["tipo"]; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="campo">
            <label for="fecha">Fecha</label>
            <div class="campo-input">
              <img src="img/iconos/calendario.svg" alt="">
              <select id="fecha" name="fecha">
                <?php foreach ($fechas as $f) { ?>
                  <option value="<?php echo $f; ?>" <?php if ($f == $fecha) echo "selected"; ?>><?php echo $dias[date("w", strtotime($f))]; ?> <?php echo date("d/m/Y", strtotime($f)); ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

          <button type="submit" class="boton-borde">Ver horarios</button>
        </form>

        <div>
          <div class="horarios-top">
            <span>Horarios disponibles</span>
            <span class="horarios-libres"><?php echo $libresCancha; ?> libres</span>
          </div>

          <div class="turnos">
            <?php foreach ($horarios as $h) { ?>
              <?php if ($h == $hora) { ?>
                <span class="turno turno-seleccionado"><?php echo $h; ?></span>
              <?php } else if (estaLibre($cancha["id"], $h, $fecha, $ocupados)) { ?>
                <a class="turno" href="<?php echo $url; ?>&cancha=<?php echo $cancha["id"]; ?>&hora=<?php echo $h; ?>"><?php echo $h; ?></a>
              <?php } else { ?>
                <span class="turno turno-ocupado"><?php echo $h; ?></span>
              <?php } ?>
            <?php } ?>
          </div>
        </div>

        <?php if ($hora != "") { ?>
          <div class="panel-total">
            <div class="panel-fila">
              <span><?php echo mb_substr($diaSemana, 0, 3); ?> <?php echo $fechaCorta; ?> · <?php echo $hora; ?> a <?php echo $horaFin; ?></span>
              <span>$<?php echo number_format($total, 0, ",", "."); ?></span>
            </div>
            <div class="panel-fila">
              <span>Total estimado</span>
              <strong>$<?php echo number_format($total, 0, ",", "."); ?></strong>
            </div>
          </div>

          <a class="boton" href="confirmar.php?cancha=<?php echo $cancha["id"]; ?>&fecha=<?php echo $fecha; ?>&hora=<?php echo $hora; ?>">
            <img src="img/iconos/reservar.svg" alt=""> Reservar este turno
          </a>
        <?php } else { ?>
          <span class="boton boton-deshabilitado">Elegí un horario</span>
        <?php } ?>

        <p class="panel-nota">Reservás en <?php echo $complejo["nombre"]; ?>. El pago se hace en el complejo.</p>
      </div>

    </div>
  </main>

  <?php include "includes/footer.php"; ?>

</body>
</html>
