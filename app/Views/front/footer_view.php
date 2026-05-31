<!--ACTUALIZACION 26/04-->
<footer class="bg-light text-muted" style="font-family: Arial, sans-serif;">
  <div class="container py-5">
    <div class="row">

      <!-- Columnas de Sección -->
      <div class="col-6 col-md-2 mb-4">
        <h6 class="text-dark">Experiencia del cliente</h6>
        <ul class="list-unstyled">
          <li><a href="Info_contact" class="text-muted text-decoration-none">Hacenos tu consulta</a></li>
          <li><a href="#" class="text-muted text-decoration-none">Comprá por Whatsapp</a></li>
        </ul>
      </div>

      <div class="col-6 col-md-2 mb-4">
        <h6 class="text-dark">Información de compra</h6>
        <ul class="list-unstyled">
          <li><a href="#" class="text-muted text-decoration-none">Medios de pago</a></li>
          <li><a href="#" class="text-muted text-decoration-none">Información de envíos</a></li>
        </ul>
      </div>

      <div class="col-6 col-md-2 mb-4">
        <h6 class="text-dark">Somos Sweet Vibes</h6>
        <ul class="list-unstyled">
          <li><a href="#" class="text-muted text-decoration-none">Sobre Nosotros</a></li>
          <li><a href="#" class="text-muted text-decoration-none">Términos y Condiciones</a></li>
        </ul>
      </div>

      <!-- Suscripción -->
      <div class="col-12 col-md-6 mb-4">
        <h6 class="text-dark">Suscríbete a nuestro boletín</h6>
        <p class="small text-muted">
          Resumen mensual de nuestras novedades y artículos más interesantes.
        </p>
        <form class="d-flex">
          <input type="email" class="form-control me-2" placeholder="Dirección de correo electrónico">
          <button type="submit" class="btn btn-dark">Suscribir</button>
        </form>
      </div>

    </div>

    <hr>

    <div class="row align-items-center">
      <div class="col-12 col-md-6 text-center text-md-start mb-3 mb-md-0">
        <small>&copy; 2022 Sweet Vibe's. Todos los derechos reservados.</small>
      </div>
      <div class="col-12 col-md-6 text-center text-md-end">
        <a href="#" class="text-muted fs-5 mx-2"><i class="fab fa-twitter"></i></a>
        <a href="#" class="text-muted fs-5 mx-2"><i class="fab fa-instagram"></i></a>
        <a href="#" class="text-muted fs-5 mx-2"><i class="fab fa-facebook-f"></i></a>
      </div>
    </div>
  </div>

<!-- js del nav -->  
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/app.js"></script>

<!-- js para desplegar la tabla de comercializacion -->
<script>
        function toggleDropdown(el) {
            const container = el.parentElement;
            const content = container.querySelector('.expand-box');
            content.classList.toggle('open');
        }
</script>

<!-- script de terminos y usos-->
<script>
  function mostrarMensaje() {
    document.getElementById("mensajeAceptado").style.display = "block";
  }
</script>

<!--script de contacto donde validamos el formulario, todos los campos -->
<script src="assets/js/contacto.js"></script>

</footer>


</html>



