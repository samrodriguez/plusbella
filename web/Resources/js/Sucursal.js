
$(document).ready(function(){
$('#dgplusbellebundle_sucursal_nombre').
        attr('data-bvalidator', 'required,required');
 
    $('#dgplusbellebundle_sucursal_direccion').
        attr('data-bvalidator', 'required,required');
		
	$('#dgplusbellebundle_sucursal_telefono').
        attr('data-bvalidator', 'number,required,required');	
		
	$('#dgplusbellebundle_sucursal_slug').
        attr('data-bvalidator', 'required,required');		
 
    //Opciones del validador
    var optionsRed = { 
        classNamePrefix: 'bvalidator_bootstraprt_', 
        lang: 'es'
    };
 
    //Validar el formulario
    $('form').bValidator(optionsRed);
	
 });//Fin document ready	
	