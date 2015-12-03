
function validarTratamientoPaciente(){
    
$('.pacienteTratamiento').
        attr('data-bvalidator', 'required,required');

$('.empleadoVentaPaquete').
        attr('data-bvalidator', 'required,required');

$('.tratamientoPaciente').
        attr('data-bvalidator', 'required,required');

$('.costoConsulta').
        attr('data-bvalidator', 'number,required,required');

$('.sesionesTratamiento').
        attr('data-bvalidator', 'min[1],number,required,required');  

$('.cuotasTratamiento').
        attr('data-bvalidator', 'min[1],number,required,required');

$('#dgplusbellebundle_personatratamiento_fechaVenta').
        attr('data-bvalidator', 'required,required');
	
 
    //Opciones del validador
    var optionsRed = { 
        classNamePrefix: 'bvalidator_bootstraprt_', 
        lang: 'es'
    };
 
    //Validar el formulario
    $('form').bValidator(optionsRed);
	
 }	
	