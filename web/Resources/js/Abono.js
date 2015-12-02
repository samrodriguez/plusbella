
function validarAbono(){
$('.montoAbono').
        attr('data-bvalidator', 'number,required,required');
		
$('.empleadoAbono').
        attr('data-bvalidator', 'required,required');				
		
$('.descripcionAbono').
        attr('data-bvalidator', 'required,required');		
	
 
    //Opciones del validador
    var optionsRed = { 
        classNamePrefix: 'bvalidator_bootstraprt_', 
        lang: 'es'
    };
 
    //Validar el formulario
    $('form').bValidator(optionsRed);
	
 }	
	