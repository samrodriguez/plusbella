
$(document).ready(function(){
$('#dgplusbellebundle_paciente_persona_nombres').
  attr('data-bvalidator', 'required,required');
  
$('#dgplusbellebundle_paciente_persona_apellidos').
  attr('data-bvalidator', 'required,required');  
  
$('#dgplusbellebundle_paciente_persona_direccion').
  attr('data-bvalidator', 'required,required');  

$('#dgplusbellebundle_paciente_persona_telefono').
  attr('data-bvalidator', 'number,required,required'); 

$('#dgplusbellebundle_paciente_persona_email').
  attr('data-bvalidator', 'email,required,required'); 

$('#dgplusbellebundle_paciente_dui').
  attr('data-bvalidator', 'required,required');  

$('#dgplusbellebundle_paciente_estadoCivil').
  attr('data-bvalidator', 'required,required');  

$('#dgplusbellebundle_paciente_sexo').
  attr('data-bvalidator', 'required,required'); 

$('#dgplusbellebundle_paciente_ocupacion').
  attr('data-bvalidator', 'required,required');   

$('#dgplusbellebundle_paciente_lugarTrabajo').
  attr('data-bvalidator', 'required,required'); 

$('#dgplusbellebundle_paciente_fechaNacimiento').
  attr('data-bvalidator', 'required,required');  

$('#dgplusbellebundle_paciente_referidoPor').
  attr('data-bvalidator', 'required,required');   

$('#dgplusbellebundle_paciente_personaEmergencia').
  attr('data-bvalidator', 'required,required'); 

$('#dgplusbellebundle_paciente_telefonoEmergencia').
  attr('data-bvalidator', 'required,required');     
	
 
    //Opciones del validador
    var optionsRed = { 
        classNamePrefix: 'bvalidator_bootstraprt_', 
        lang: 'es'
    };
 
    //Validar el formulario
    $('form').bValidator(optionsRed);
	
 });//Fin document ready	
	