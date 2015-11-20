
$(document).ready(function(){
$('#dgplusbellebundle_plantilla_nombre').
        attr('data-bvalidator', 'required,required');
 
    $('#dgplusbellebundle_plantilla_descripcion').
        attr('data-bvalidator', 'required,required');
		

    //Opciones del validador
    var optionsRed = { 
        classNamePrefix: 'bvalidator_bootstraprt_', 
        lang: 'es'
    };
 
    //Validar el formulario
    $('form').bValidator(optionsRed);
	
 });//Fin document ready	
	