<?php
$session = session();
$nombre = $session->get('nombre');
$perfil=$session->get('perfil_id');
$usuarioid=$session->get('id_usuario');
?>

<header class="header-nav">
<nav class="navbar navbar-expand-lg bg-body-tertiary fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand pacifico-regular" href="Casa">SV</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <!--ACTUALIZACION: ADMIN logueado-->
    <?php if(session()->perfil_id == 1): ?>
      <div class="btn btn-info active btnUser btn-sm">
        <a href="">ADMIN: <?php echo session('nombre'); ?> </a>
     </div>

     <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="<?php echo base_url('Casa');?>">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url('/usuarios');?>">CRUD Usuarios</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url('crear');?>">CRUD Productos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url('/ventas');?>">CRUD Ventas</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url('listar_consultas');?>">Consultas</a>
        </li>

        <!--<li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Mas
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href='Info_contact'>Informacion de contacto</a></li>
            <li><a class="dropdown-item" href='Terminos_Uso'>Terminos de Uso</a></li>
            
          </ul>
        </li>-->
        <a class="btn btn-danger btn-sm me-2" href="<?= base_url('logout'); ?>">Cerrar sesión</a>
        <li class="nav-item">
          <a class="nav-link disabled" aria-disabled="true"></a>
        </li>
      </ul>
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
     </div>
      <!--CLIENTE logueado-->
    <?php elseif(session()->perfil_id == 2): ?>
      <div class="btn btn-info active btnUser btn-sm">
        <a href="">CLIENTE: <?php echo session('nombre'); ?> </a>
      </div>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="<?php echo base_url('Casa');?>">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url('quienesSomos');?>">Quienes Somos</a>

        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url('Comercializacion');?>">Comercializacion</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="<?php echo base_url('catalogo');?>">Productos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="<?php echo base_url('/mis-compras/' . session()->id_usuario);?>">Mis compras</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url('carrito');?>">
              <i class="fas fa-shopping-cart"></i>
              <?php if(session()->has('cart') && count(session('cart')) > 0): ?>
                  <span class="badge bg-danger">
                      <?php 
                      $count = 0;
                      foreach(session('cart') as $item) {
                          $count += $item['quantity'];
                      }
                      echo $count;
                      ?>
                  </span>
              <?php endif; ?>
          </a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Mas
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?php echo base_url('Info_contact/' . session()->id_usuario);?>">Informacion de contacto</a></li>
            <li><a class="dropdown-item" href="<?php echo base_url('Terminos_Uso');?>">Terminos de Uso</a></li>
          </ul>
        </li>
        <a class="btn btn-danger btn-sm me-2" href="<?= base_url('logout'); ?>">Cerrar sesión</a>
        <li class="nav-item">
          <a class="nav-link disabled" aria-disabled="true"></a>
        </li>
      </ul>
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
    <?php else: ?>
    <!-- Nav para cuando no hay sesión o perfil desconocido -->
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="<?php echo base_url('Casa');?>">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url('quienesSomos');?>">Quienes Somos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url('Comercializacion');?>">Comercializacion</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url('registro');?>">Registrarse</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href= "<?php echo base_url('Login');?>" >Iniciar sesión</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url('catalogo');?>">Productos</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Mas
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?php echo base_url('Info_contact');?>">Informacion de contacto</a></li>
            <li><a class="dropdown-item" href="<?php echo base_url('Terminos_Uso');?>">Terminos de Uso</a></li>
          </ul>
        </li>
      </ul>
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
<?php endif; ?>
  </div>
</nav>
</header>