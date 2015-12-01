
function validarVentaPaquete(){
$('.empleadoVentaPaquete').
attr('data-bvalidator', 'required,required');

$('.paqueteVenta').
attr('data-bvalidator', 'required,required');

$('.cuotas').
attr('data-bvalidator', 'required,required');

$('.calZebra').
attr('data-bvalidator', 'required,required');

    //Opciones del validador
    var optionsRed = { 
        classNamePrefix: 'bvalidator_bootstraprt_', 
        lang: 'es'
    };
 
    //Validar el formulario
    $('form').bValidator(optionsRed);
	
 }	
	