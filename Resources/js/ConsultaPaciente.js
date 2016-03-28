function validarConsulta(){
    
$('.fechaConsulta').
  attr('data-bvalidator', 'required,required');  
  
$('.tratamientoConsulta').
  attr('data-bvalidator', 'required,required');       
  
$('.costoConsulta').
  attr('data-bvalidator', 'number,required,required'); 
  
$('.tipoConsulta').
  attr('data-bvalidator', 'required,required'); 
  
$('#dgplusbellebundle_consulta_paciente').
  attr('data-bvalidator', 'required,required');   
  
$('.corporal').
  attr('data-bvalidator', 'required,required'); 
    
//$('.observacionConsulta').
//  attr('data-bvalidator', 'required,required');
  
//$('.productoConsulta').
//  attr('data-bvalidator', 'required,required');  
  
//$('.indicacionesConsulta').
//  attr('data-bvalidator', 'required,required');  
  
$('.classPlantilla').
  attr('data-bvalidator', 'required,required');       
   
   $('.sucursalPaquete').
attr('data-bvalidator', 'required,required');
    //Opciones del validador
    var optionsRed = { 
        classNamePrefix: 'bvalidator_bootstraprt_', 
        lang: 'es'
    };
 
    //Validar el formulario
    $('form').bValidator(optionsRed);
	
 }	
	