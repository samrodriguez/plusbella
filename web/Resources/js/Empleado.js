
 function validarEmpleado(){
$('.nombresPersona').
  attr('data-bvalidator', 'required,required');
  
$('.apellidosPersona').
  attr('data-bvalidator', 'required,required');  
  
$('.telefonoPersona').
  attr('data-bvalidator', 'minlength[9],required,required');  

$('.direccionPersona').
  attr('data-bvalidator', 'required,required'); 

$('.emailPersona').
  attr('data-bvalidator', 'email,required,required'); 

$('.cargoEmpleado').
  attr('data-bvalidator', 'required,required');  

$('.sucursalEmpleado').
  attr('data-bvalidator', 'required,required'); 
  
$('.tratamientoEmpleado > div > label > input').
  attr('data-bvalidator', 'required,required');   

 
    //Opciones del validador
    var optionsRed = { 
        classNamePrefix: 'bvalidator_bootstraprt_', 
        lang: 'es'
    };
 
    //Validar el formulario
    $('form').bValidator(optionsRed);
	
 }	
	