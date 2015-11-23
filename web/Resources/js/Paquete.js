
$(document).ready(function(){
$('#dgplusbellebundle_paquete_nombre').
        attr('data-bvalidator', 'required,required');
 
    $('#dgplusbellebundle_paquete_costo').
        attr('data-bvalidator', 'number,required,required');
 
    //Opciones del validador
    var optionsRed = { 
        classNamePrefix: 'bvalidator_bootstraprt_', 
        lang: 'es'
    };
 
    //Validar el formulario
    $('form').bValidator(optionsRed);
	
 });//Fin document ready	
	