
$(document).ready(function(){
$('#dgplusbellebundle_consulta_fechaConsulta').
attr('data-bvalidator', 'required,required');

$('#dgplusbellebundle_consulta_tratamiento').
attr('data-bvalidator', 'required,required');

$('#dgplusbellebundle_consulta_tipoConsulta').
attr('data-bvalidator', 'required,required');
	
 
    //Opciones del validador
    var optionsRed = { 
        classNamePrefix: 'bvalidator_bootstraprt_', 
        lang: 'es'
    };
 
    //Validar el formulario
    $('form').bValidator(optionsRed);
	
 });//Fin document ready	
	