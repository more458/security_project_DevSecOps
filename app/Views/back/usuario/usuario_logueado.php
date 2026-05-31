<!--imagenes para los perfiles-->
<div class="container mt-5">
	<div class="row justify-content-md-center">
		<div class="col-5">

			
			<?php if(session()->getFlashdata('msg')):?><!--sta línea verifica si hay un mensaje flash almacenado en la sesión y, si es así, ejecuta el bloque de código que le sigue.-->
			<div> class = "alert alert-warning">
				<?=session()->getFlashdata('msg')?><!--Esta línea muestra el mensaje flash almacenado en la sesión dentro del elemento de alerta.-->
			</div>
			<?php endif;?>


			<br><br>
			<!-- Esta línea verifica si el valor de la variable perfil_id en la sesión es igual a 1 y, si es así, ejecuta el bloque de código que le sigue que muestra una imagen.-->
			<?php if(session()->perfil_id == 1): ?>
				<div>
			<img class="center" height="100px" width="100px"src="<?php echo base_url ('assets/img/admin.png');?>">
		</div>

		    <!-- Esta línea verifica si el valor de la variable perfil_id en la sesión es igual a 2 y, si es así, ejecuta el bloque de código que le sigue que muestra una imagen.-->
		<?php elseif(session()->perfil_id == 2): ?>
			<div>
				<img class="center" height="100px" width="100px"src="<?php echo base_url ('assets/img/cliente.png');?>">
			</div>

		<?php endif;?>
	</div>
</div>
</div> 