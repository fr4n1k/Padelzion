<div class="testimonio">
  <div class="estrellas">
    <?php $estrella = 1; while ($estrella <= $testimonio["estrellas"]) { ?>
      <img src="img/iconos/estrella.svg" alt="">
    <?php $estrella++; } ?>
  </div>
  <p class="cita"><?php echo $testimonio["cita"]; ?></p>
  <div class="autor">
    <img src="img/<?php echo $testimonio["imagen"]; ?>" alt="<?php echo $testimonio["nombre"]; ?>">
    <div>
      <p class="autor-nombre"><?php echo $testimonio["nombre"]; ?></p>
      <p class="autor-meta"><?php echo $testimonio["meta"]; ?></p>
    </div>
  </div>
</div>
