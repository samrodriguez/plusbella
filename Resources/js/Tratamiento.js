
 function validarTratamiento(){
$('.categoriaTratamiento').
        attr('data-bvalidator', 'required,required');
 
$('.nombreTratamiento').
        attr('data-bvalidator', 'required,required');

$('.costoTratamiento').
        attr('data-bvalidator', 'number,required,required');

$('.sucursalTratamiento > div > label > input').
        attr('data-bvalidator', 'number,required,required');	

    //Opciones del validador
    var optionsRed = { 
        classNamePrefix: 'bvalidator_bootstraprt_', 
        lang: 'es'
    };
 
    //Validar el formulario
    $('form').bValidator(optionsRed);
	
 }	
	