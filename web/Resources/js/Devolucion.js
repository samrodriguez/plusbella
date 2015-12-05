
function validarDevolucion(){
$('.montoDevolucion').
        attr('data-bvalidator', 'number,required,required');

$('.paqueteDevolucion').
        attr('data-bvalidator', 'number,required,required');

$('.tratamientoDevolucion').
        attr('data-bvalidator', 'number,required,required');
		
$('.empleadoDevolucion').
        attr('data-bvalidator', 'required,required');				
		
$('.motivoDevolucion').
        attr('data-bvalidator', 'required,required');		
	
 
    //Opciones del validador
    var optionsRed = { 
        classNamePrefix: 'bvalidator_bootstraprt_', 
        lang: 'es'
    };
 
    //Validar el formulario
    $('form').bValidator(optionsRed);
	
 }	
	