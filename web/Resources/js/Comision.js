
$(document).ready(function(){
	
$('#dgplusbellebundle_comision_descripcion').
        attr('data-bvalidator', 'required,required');
		
$('#dgplusbellebundle_comision_porcentaje').
        attr('data-bvalidator', 'between[0:100],required,required');

$('#dgplusbellebundle_comision_meta').
        attr('data-bvalidator', 'number,required,required');		
	
$('#dgplusbellebundle_comision_empleado').
        attr('data-bvalidator', 'required,required');
 
    //Opciones del validador
    var optionsRed = { 
        classNamePrefix: 'bvalidator_bootstraprt_', 
        lang: 'es'
    };
 
    //Validar el formulario
    $('form').bValidator(optionsRed);
	
 });//Fin document ready	
	