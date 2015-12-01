
$(document).ready(function(){
$('#dgplusbellebundle_empleado_persona_nombres').
  attr('data-bvalidator', 'required,required');
  
$('#dgplusbellebundle_empleado_persona_apellidos').
  attr('data-bvalidator', 'required,required');  
  
$('#dgplusbellebundle_empleado_persona_telefono').
  attr('data-bvalidator', 'minlength[9],required,required');  

$('#dgplusbellebundle_empleado_persona_direccion').
  attr('data-bvalidator', 'required,required'); 

$('#dgplusbellebundle_empleado_persona_email').
  attr('data-bvalidator', 'email,required,required'); 

$('#dgplusbellebundle_empleado_cargo').
  attr('data-bvalidator', 'required,required');  

$('#dgplusbellebundle_empleado_sucursal').
  attr('data-bvalidator', 'required,required');  

 
    //Opciones del validador
    var optionsRed = { 
        classNamePrefix: 'bvalidator_bootstraprt_', 
        lang: 'es'
    };
 
    //Validar el formulario
    $('form').bValidator(optionsRed);
	
 });//Fin document ready	
	