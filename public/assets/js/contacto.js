//VALIDAMOS TODOS LOS CAMPOS

function validarFormulario(event) {
    //PONE UN ALTO
    event.preventDefault(); // Evita que se envíe el formulario automáticamente al hacer clic en el botón de envío
  
    let esValido = true; // Variable que indica si el formulario es válido (inicia como verdadero)
    let mensajes = []; // Array para almacenar los mensajes de error que se generen
  
    // CONSTANTES: Obtiene una referencia a los elementos del formulario por su ID
    const nombre = document.getElementById('inputNombre');
    const apellido = document.getElementById('inputApellido');
    const email = document.getElementById('inputEmail4');
    const motivo = document.getElementById('inputMotivoConsulta');
    const direccion = document.getElementById('inputAddress');
    const provincia = document.getElementById('inputCity');
    const ciudad = document.getElementById('inputState');
    const descripcion = document.getElementById('inputDescripcion');
    const alerta = document.getElementById('alertaErrores'); // Elemento donde se mostrarán los mensajes de error
  
    // Expresión regular para validar nombres que solo contengan letras y espacios
    const soloLetras = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/;
    // Expresión regular para validar un correo electrónico
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  
    //CAMPO CON ERROR
    // Función para marcar un campo con error (bordes rojos) si la condición es verdadera
    function marcarError(campo, error = true) {
      campo.style.border = error ? "2px solid red" : ""; // Cambia el borde del campo a rojo si hay error, o lo elimina si no hay error
    }
  
    //VALIDACION
    // Función que valida un campo con base en una condición y un mensaje de error
    function validarCampo(campo, condicion, mensaje) {
      if (condicion) {
        marcarError(campo); // Marca el campo con error
        mensajes.push(mensaje); // Agrega el mensaje de error al array
        esValido = false; // Marca el formulario como no válido
      } else {
        marcarError(campo, false); // Si no hay error, elimina el borde rojo
      }
    }
  
    // validación de los campos del formulario
    validarCampo(nombre, nombre.value.trim() === "" || nombre.value.length > 20 || !soloLetras.test(nombre.value), "Nombre inválido (solo letras, máx. 20 caracteres).");
    validarCampo(apellido, apellido.value.trim() === "" || apellido.value.length > 20 || !soloLetras.test(apellido.value), "Apellido inválido (solo letras, máx. 20 caracteres).");
    validarCampo(email, email.value.trim() === "" || !emailRegex.test(email.value), "Correo electrónico inválido.");
    validarCampo(motivo, motivo.value.trim() === "", "El motivo de consulta es obligatorio.");
    validarCampo(direccion, direccion.value.trim() === "", "La dirección es obligatoria.");
    validarCampo(provincia, provincia.value.trim() === "", "La provincia es obligatoria.");
    validarCampo(ciudad, ciudad.value === "Choose..." || ciudad.value.trim() === "", "Debe seleccionar una ciudad.");
    validarCampo(descripcion, descripcion.value.trim() === "", "La descripción es obligatoria.");
  
    // FORM INVALIDO: se muestra un mensaje de error
    if (!esValido) {
      alerta.innerHTML = "<strong>Por favor, corregí los siguientes errores:</strong><ul><li>" + mensajes.join("</li><li>") + "</li></ul>";
      alerta.classList.remove("d-none"); // Muestra el alerta de errores
    } else {
      alerta.classList.add("d-none"); // si no hay errores, oculta la alerta
      alert("Formulario enviado con éxito!"); // éxito
      location.reload(); //para borrar los datos recargamos o actulizamos la pagina

    }
  }
  

