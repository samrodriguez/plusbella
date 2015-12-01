
function validarPlantilla(){
$('.nombrePlantilla').
        attr('data-bvalidator', 'required,required');
 
$('.descripcionPlantilla').
        attr('data-bvalidator', 'required,required');

$('.nombreDetalle').
        attr('data-bvalidator', 'required,required');

$('.descripcionDetalle').
        attr('data-bvalidator', 'required,required');    

    //Opciones del validador
    var optionsRed = { 
        classNamePrefix: 'bvalidator_bootstraprt_', 
        lang: 'es'
    };
 
    //Validar el formulario
    $('form').bValidator(optionsRed);
	
 }	
	
