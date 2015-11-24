
 function validarPaquete(){
$('.nombrePaquete').
        attr('data-bvalidator', 'required,required');
 
$('.costoPaquete').
        attr('data-bvalidator', 'number,required,required');

$('.tratamientoPaquete').
        attr('data-bvalidator', 'required,required');

$('.sesionesPaquete').
        attr('data-bvalidator', 'number,required,required');

 $('.sucursalPaquete > div > label > input').
        attr('data-bvalidator', 'required,required');
 
    //Opciones del validador
    var optionsRed = { 
        classNamePrefix: 'bvalidator_bootstraprt_', 
        lang: 'es'
    };
 
    //Validar el formulario
    $('form').bValidator(optionsRed);
	
 }	
	