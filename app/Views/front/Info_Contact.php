<?php $validation = \Config\Services::validation(); ?>

<?php
$session = \Config\Services::session();//instansiamos la sesion

//inicializamos 
$id_usuario = null;
$nombre = '';
$apellido = '';
$email = '';
$telefono = ''; 
$mensaje = '';  

// asi verificamos si el usuario está logueado y entonces guardamos sus datos en las variables
if ($session->get('isLoggedIn')) {
    $id_usuario = $session->get('id_usuario'); // Si guardas el ID del usuario en sesión
    $nombre = $session->get('nombre_usuario');
    $apellido = $session->get('apellido_usuario');
    $email = $session->get('email_usuario');
}

?>

<body class="contact-body">
    <div class="titulo">
        <h1 class="contact-h1"><?php echo $titulo="Información de Contacto"?></h1>
    </div>
    <!-- IMAGEN DE FONDO -->
    <div class="imagen-fondo">
        <img src="<?= base_url('assets/img/fondo.jpg') ?>" alt="fondo">
    </div>

    <!-- INFO DEL CONTACTO -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-5 bg-light p-4 rounded shadow">
                <h5>Información de Contacto</h5>
                <p><strong>Nombre del Titular:</strong> María López</p>
                <p><strong>Razón Social:</strong> Sweet Vibe's S.R.L.</p>
                <p><strong>Domicilio Legal:</strong> Av. de los Sabores 1234, CABA, Buenos Aires, Argentina</p>
                <p><strong>Teléfonos:</strong> (011) 4567-8910 / +54 9 11 2345-6789</p>
                <p><strong>Email:</strong> contacto@sweetvibes.com.ar</p>
                <p><strong>Redes Sociales:</strong></p>
                <ul class="list-unstyled">
                    <li><i class="fab fa-facebook text-primary me-2"></i>Facebook</li>
                    <li><i class="fab fa-instagram text-danger me-2"></i>Instagram</li>
                    <li><i class="fab fa-twitter text-info me-2"></i>Twitter/X</li>
                </ul>
                <p><strong>Horario de Atención:</strong> Lunes a Viernes de 9:00 a 18:00 hs.</p>
            </div>
            <!-- FORMULARIO -->
            <!-- usamos set_value() se encargará de rellenar los campos.-->
            <div class="col-md-7">
                <form class="row g-3 mt-2 shadow p-4 rounded bg-light" action="<?= base_url('enviar-consul') ?>" method="post">
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input class="form-control" type="text" name="nombre" id="nombre"
                            value="<?= set_value('nombre', $nombre); ?>"
                            placeholder="Nombre" autofocus>

                        <?php if ($validation->hasError('nombre')): ?>
                            <div class="alert alert-danger mt-2">
                                <?= $validation->getError('nombre'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input class="form-control" type="text" name="apellido" id="apellido"
                            value="<?= set_value('apellido', $apellido); ?>"
                            placeholder="Apellido" autofocus>

                        <?php if ($validation->hasError('apellido')): ?>
                            <div class="alert alert-danger mt-2">
                                <?= $validation->getError('apellido'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input class="form-control" type="text" name="email" id="email"
                            value="<?= set_value('email', $email); ?>"
                            placeholder="Email" autofocus>

                        <?php if ($validation->hasError('email')): ?>
                            <div class="alert alert-danger mt-2">
                                <?= $validation->getError('email'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label for="telefono" class="form-label">Telefono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" value="<?= set_value('telefono', $telefono); ?>"><!--agregamos atrib name para que el controlador pueda recibirlo-->
                        <?php if ($validation->hasError('telefono')): ?>
                            <div class="alert alert-danger mt-2">
                                <?= $validation->getError('telefono'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-12">
                        <label for="mensaje" class="form-label">Mensaje</label>
                        <input type="text" class="form-control" id="mensaje" name="mensaje" placeholder="..." value="<?= set_value('mensaje', $mensaje); ?>">
                        <?php if ($validation->hasError('mensaje')): ?>
                            <div class="alert alert-danger mt-2">
                                <?= $validation->getError('mensaje'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div id="alertaErrores" class="alert alert-danger d-none" role="alert">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <br><br>

</body>