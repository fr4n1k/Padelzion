<?php
include "includes/sesion.php";
include "config/conexion.php";

$pagina = "inicio";

$sql = "SELECT * FROM complejos LIMIT 3";
$complejos = $conn->query($sql);

$sql = "SELECT COUNT(*) AS total FROM complejos";
$resultado = $conn->query($sql);
$total = $resultado->fetch_assoc()["total"];

$sql = "SELECT * FROM testimonios ORDER BY id";
$testimonios = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Padelzion</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

  <?php include "includes/navbar.php"; ?>

  <!-- Hero -->
  <section class="hero">
    <div class="hero-contenido">
      <h1>Reservá tu cancha de pádel en un minuto.</h1>
      <p>Elegí complejo, día y horario. Confirmás, pagás y tu reserva queda en “Mis reservas”, donde podés cancelarla si no llegás.</p>
    </div>

    <form class="busqueda" action="complejos.php" method="GET">
      <div class="campo">
        <label for="buscar">Nombre del complejo</label>
        <div class="campo-input">
          <img src="img/iconos/buscar.svg" alt="">
          <input type="text" id="buscar" name="buscar" placeholder="Buscá por nombre, ej: Aconcagua Pádel Club">
        </div>
      </div>
      <button type="submit" class="boton-buscar"><img src="img/iconos/buscar-blanco.svg" alt=""> Buscar</button>
    </form>
  </section>

  <!-- Complejos destacados -->
  <section class="seccion seccion-gris">
    <div class="seccion-encabezado">
      <p class="overline">COMPLEJOS</p>
      <h2>Elegí dónde jugar</h2>
    </div>

    <div class="grilla">
      <?php while ($complejo = $complejos->fetch_assoc()) { ?>
        <?php include "includes/card-complejo.php"; ?>
      <?php } ?>
    </div>

    <div class="ver-todos">
      <a href="complejos.php" class="boton-borde">Ver los <?php echo $total; ?> complejos</a>
    </div>
  </section>

  <!-- Como funciona -->
  <section class="seccion">
    <div class="seccion-encabezado">
      <p class="overline">CÓMO FUNCIONA</p>
      <h2>Reservá en 3 pasos</h2>
      <p class="descripcion">Sin vueltas: elegís, confirmás y gestionás tus reservas desde un solo lugar.</p>
    </div>

    <div class="pasos">
      <div class="paso paso-activo">
        <div class="paso-top">
          <span class="paso-icono"><img src="img/iconos/paso-buscar.svg" alt=""></span>
          <span class="paso-numero">01</span>
        </div>
        <h3>Elegí complejo y horario</h3>
        <p>Buscá por zona y día, entrá al complejo y tocá el horario libre que te sirva.</p>
      </div>

      <div class="paso">
        <div class="paso-top">
          <span class="paso-icono"><img src="img/iconos/paso-confirmar.svg" alt=""></span>
          <span class="paso-numero">02</span>
        </div>
        <h3>Confirmá la reserva</h3>
        <p>Revisás el resumen, pagás y listo: te llega el comprobante por mail.</p>
      </div>

      <div class="paso">
        <div class="paso-top">
          <span class="paso-icono"><img src="img/iconos/paso-gestionar.svg" alt=""></span>
          <span class="paso-numero">03</span>
        </div>
        <h3>Gestioná tus reservas</h3>
        <p>En “Mis reservas” ves tus próximos turnos y podés cancelarlos hasta 24 h antes sin cargo.</p>
      </div>
    </div>
  </section>

  <!-- Testimonios -->
  <section class="seccion seccion-gris">
    <div class="seccion-encabezado">
      <p class="overline">TESTIMONIOS</p>
      <h2>Lo que dicen quienes juegan acá</h2>
    </div>

    <div class="testimonios">
      <?php while ($testimonio = $testimonios->fetch_assoc()) { ?>
        <?php include "includes/card-testimonio.php"; ?>
      <?php } ?>
    </div>
  </section>

  <?php include "includes/footer.php"; ?>

</body>
</html>
