function validarCita(){

$('.busqueda').
attr('data-bvalidator', 'required,required');

$('.fechaCita').
attr('data-bvalidator', 'required,required');

$('.descuentoCita').
attr('data-bvalidator', 'required,required');

$('.sucursalEmpleado ').
attr('data-bvalidator', 'required,required');


$('.tratamientoCita').
attr('data-bvalidator', 'required,required');

    //Opciones del validador
    var optionsRed = { 
        classNamePrefix: 'bvalidator_bootstraprt_', 
        
        lang: 'es'
    };
 
    //Validar el formulario
    $('form').bValidator(optionsRed);
	
 }	
	
