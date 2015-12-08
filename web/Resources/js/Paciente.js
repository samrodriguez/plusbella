
function validarPaciente(){
$('.nombresPersona').
  attr('data-bvalidator', 'required,required');
  
$('.apellidosPersona').
  attr('data-bvalidator', 'required,required');  
  
$('.direccionPersona').
  attr('data-bvalidator', 'required,required');  

$('.telefonoPersona').
  attr('data-bvalidator', 'minlength[9],required,required'); 

$('.emailPersona').
  attr('data-bvalidator', 'required,required'); 

$('.duiPaciente').
  attr('data-bvalidator', 'minlength[10],required,required');  

$('.estadocivilPaciente').
  attr('data-bvalidator', 'required,required');  

$('.sexoPaciente').
  attr('data-bvalidator', 'required,required'); 

$('.ocupacionPaciente').
  attr('data-bvalidator', 'required,required');   

$('.lugarTrabajoPaciente').
  attr('data-bvalidator', 'required,required'); 

$('#dgplusbellebundle_paciente_fechaNacimiento').
  attr('data-bvalidator', 'required,required');  

$('.referidoPorPaciente').
  attr('data-bvalidator', 'required,required');   

$('.emergenciaPaciente').
  attr('data-bvalidator', 'required,required'); 

$('.telefonoemerPaciente').
  attr('data-bvalidator', 'minlength[9],required,required');     
	
 
    //Opciones del validador
    var optionsRed = { 
        classNamePrefix: 'bvalidator_bootstraprt_', 
        lang: 'es'
    };
 
    //Validar el formulario
    $('form').bValidator(optionsRed);
	
 }	
	