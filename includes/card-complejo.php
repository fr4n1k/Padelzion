<a class="card" href="complejo.php?id=<?php echo $complejo["id"]; ?>">
  <div class="card-imagen">
    <img src="img/<?php echo $complejo["imagen"]; ?>" alt="<?php echo $complejo["nombre"]; ?>">
    <span class="etiqueta"><img src="img/iconos/ubicacion.svg" alt=""> <?php echo $complejo["zona"]; ?></span>
  </div>

  <div class="card-cuerpo">
    <h3><?php echo $complejo["nombre"]; ?></h3>

    <div class="card-rasgos">
      <span><img src="img/iconos/canchas.svg" alt=""> <?php echo $complejo["cantidad_canchas"]; ?> canchas</span>
      <span><img src="img/iconos/estrella.svg" alt=""> <?php echo number_format($complejo["puntaje"], 1, ",", "."); ?> (<?php echo $complejo["opiniones"]; ?>)</span>
    </div>

    <div class="card-pie">
      <div>
        <p class="card-desde">Desde</p>
        <p class="card-precio">$<?php echo number_format($complejo["precio_desde"], 0, ",", "."); ?> <span>/ hora</span></p>
      </div>
      <span class="boton-borde">Ver horarios</span>
    </div>
  </div>
</a>
