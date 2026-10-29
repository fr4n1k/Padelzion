<?php
$fechaReserva = strtotime($reserva["fecha"]);
$horaFin = date("H:i", strtotime($reserva["hora"]) + 90 * 60);
$codigo = "PZ-" . date("y", strtotime($reserva["creada"])) . "-" . str_pad($reserva["id"], 5, "0", STR_PAD_LEFT);
$sePuedeCancelar = $tab == "proximas" && $reserva["fecha"] . " " . $reserva["hora"] > $limite;
?>
<div class="card-reserva">
  <img class="card-reserva-foto" src="img/<?php echo $reserva["foto"]; ?>" alt="<?php echo $reserva["complejo"]; ?>">

  <div class="card-reserva-info">
    <div class="card-reserva-top">
      <?php if ($tab == "proximas") { ?>
        <span class="estado estado-confirmada"><span class="punto"></span> Confirmada</span>
      <?php } else if ($tab == "pasadas") { ?>
        <span class="estado estado-jugada"><span class="punto"></span> Jugada</span>
      <?php } else { ?>
        <span class="estado estado-cancelada"><span class="punto"></span> Cancelada</span>
      <?php } ?>
      <span><?php echo $codigo; ?> · <?php echo $reserva["complejo"]; ?></span>
    </div>

    <h3><?php echo $reserva["cancha"]; ?> · <?php echo $reserva["tipo"]; ?></h3>

    <div class="card-reserva-meta">
      <span><img src="img/iconos/calendario.svg" alt=""> <?php echo $dias[date("w", $fechaReserva)]; ?> <?php echo date("d/m/Y", $fechaReserva); ?></span>
      <span><img src="img/iconos/reloj.svg" alt=""> <?php echo $reserva["hora"]; ?> a <?php echo $horaFin; ?></span>
      <?php if ($tab == "proximas") { ?>
        <span class="card-reserva-pago"><img src="img/iconos/pago-gris.svg" alt=""> Pagás en el complejo al llegar</span>
      <?php } ?>
    </div>
  </div>

  <div class="card-reserva-derecha">
    <div>
      <p class="card-reserva-label">Total</p>
      <p class="card-reserva-total">$<?php echo number_format($reserva["total"], 0, ",", "."); ?></p>
    </div>

    <?php if ($sePuedeCancelar) { ?>
      <a class="link-cancelar" href="mis-reservas.php?cancelar=<?php echo $reserva["id"]; ?>">Cancelar reserva</a>
    <?php } else if ($tab == "proximas") { ?>
      <span class="card-reserva-label">Faltan menos de 24 h</span>
    <?php } ?>
  </div>
</div>
