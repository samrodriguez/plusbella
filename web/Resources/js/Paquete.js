
 function validarPaquete(){
$('.nombrePaquete').
        attr('data-bvalidator', 'required,required');
 
$('.costoPaquete').
        attr('data-bvalidator', 'number,required,required');

 $('.sucursalPaquete').
        attr('data-bvalidator', 'required,required');
 
    //Opciones del validador
    var optionsRed = { 
        classNamePrefix: 'bvalidator_bootstraprt_', 
        lang: 'es'
    };
 
    //Validar el formulario
    $('form').bValidator(optionsRed);
	
 }	
	