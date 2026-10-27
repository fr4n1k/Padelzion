<nav class="navbar">
  <div class="navbar-izquierda">
    <a href="index.php" class="logo">
      <img src="img/iconos/logo.png" alt="Padelzion">
      <span>Padelzion</span>
    </a>

    <div class="navbar-links">
      <a href="index.php" class="<?php if ($pagina == "inicio") echo "activo"; ?>">Inicio</a>
      <a href="complejos.php" class="<?php if ($pagina == "complejos") echo "activo"; ?>">Complejos</a>
      <a href="mis-reservas.php" class="<?php if ($pagina == "mis-reservas") echo "activo"; ?>">Mis reservas</a>
    </div>
  </div>

  <div class="usuario">
    <div class="usuario-boton">
      <span class="avatar"><?php echo $_SESSION["usuario_iniciales"]; ?></span>
      <span><?php echo $_SESSION["usuario_nombre"]; ?></span>
      <img src="img/iconos/flecha-abajo.svg" alt="">
    </div>

    <div class="usuario-menu">
      <a href="logout.php"><img src="img/iconos/cerrar-sesion.svg" alt=""> Cerrar sesión</a>
    </div>
  </div>
</nav>
