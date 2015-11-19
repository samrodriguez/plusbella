
$(document).ready(function(){

$('#dgplusbellebundle_cita_empleado').
attr('data-bvalidator', 'required,required');

$('#dgplusbellebundle_cita_fechaCita').
attr('data-bvalidator', 'required,required');

$('#dgplusbellebundle_cita_descuento').
attr('data-bvalidator', 'required,required');

$('#dgplusbellebundle_cita_tratamiento').
attr('data-bvalidator', 'required,required');

$('#dgplusbellebundle_consulta_incapacidad').
attr('data-bvalidator', 'required,required');

$('#dgplusbellebundle_consulta_reportePlantilla').
attr('data-bvalidator', 'required,required');
	
 
    //Opciones del validador
    var optionsRed = { 
        classNamePrefix: 'bvalidator_bootstraprt_', 
        
        lang: 'es'
    };
 
    //Validar el formulario
    $('form').bValidator(optionsRed);
	
 });//Fin document ready	
	
