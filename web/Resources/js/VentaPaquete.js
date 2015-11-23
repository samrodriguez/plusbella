
$(document).ready(function(){
$('#dgplusbellebundle_ventapaquete_empleado').
attr('data-bvalidator', 'required,required');

$('#dgplusbellebundle_ventapaquete_paquete').
attr('data-bvalidator', 'required,required');

$('#dgplusbellebundle_ventapaquete_fechaVenta').
attr('data-bvalidator', 'required,required');
	
 
    //Opciones del validador
    var optionsRed = { 
        classNamePrefix: 'bvalidator_bootstraprt_', 
        lang: 'es'
    };
 
    //Validar el formulario
    $('form').bValidator(optionsRed);
	
 });//Fin document ready	
	