<?php
include "includes/sesion.php";
include "config/conexion.php";

$pagina = "complejos";

$buscar = "";
if (isset($_GET["buscar"])) {
  $buscar = $_GET["buscar"];
}

$filtro = "%" . $buscar . "%";

$sql = "SELECT * FROM complejos WHERE nombre LIKE ? ORDER BY id";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $filtro);
$stmt->execute();
$complejos = $stmt->get_result();
$total = $complejos->num_rows;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Complejos - Padelzion</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body class="fondo-gris">

  <?php include "includes/navbar.php"; ?>

  <main class="contenido">

    <!-- Encabezado -->
    <div class="encabezado-pagina">
      <div class="breadcrumb">
        <a href="index.php">Inicio</a>
        <img src="img/iconos/flecha-derecha.svg" alt="">
        <span>Complejos</span>
      </div>
      <h1>Complejos de pádel</h1>
      <p>Elegí un complejo para ver sus canchas y turnos libres. Los precios son por hora y varían según la franja.</p>
    </div>

    <!-- Filtros -->
    <form class="filtros" action="complejos.php" method="GET">
      <div class="campo">
        <label for="buscar">Nombre del complejo</label>
        <div class="campo-input">
          <img src="img/iconos/buscar.svg" alt="">
          <input type="text" id="buscar" name="buscar" placeholder="Buscá por nombre, ej: Aconcagua Pádel Club" value="<?php echo htmlspecialchars($buscar); ?>">
        </div>
      </div>
      <button type="submit" class="boton-buscar"><img src="img/iconos/buscar-blanco.svg" alt=""> Buscar</button>
    </form>

    <!-- Resultados -->
    <?php if ($total == 1) { ?>
      <p class="resultados-total">1 complejo encontrado</p>
    <?php } else { ?>
      <p class="resultados-total"><?php echo $total; ?> complejos encontrados</p>
    <?php } ?>

    <?php if ($total == 0) { ?>
      <div class="sin-resultados">
        <p>No encontramos complejos con ese nombre. Probá con otra búsqueda.</p>
        <a href="complejos.php" class="boton-borde">Ver todos los complejos</a>
      </div>
    <?php } else { ?>
      <div class="grilla-complejos">
        <?php while ($complejo = $complejos->fetch_assoc()) { ?>
          <?php include "includes/card-complejo.php"; ?>
        <?php } ?>
      </div>
    <?php } ?>

  </main>

  <?php include "includes/footer.php"; ?>

</body>
</html>
