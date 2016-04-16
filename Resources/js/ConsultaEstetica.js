function validarConsultaEstetica(){
    
$('.corporall').
  attr('data-bvalidator', 'required,required');  
  
$('#dgplusbellebundle_consulta_estetica').
  attr('data-bvalidator', 'required,required');   

$('.classCorporal').
  attr('data-bvalidator', 'min[1], required, required');  
   
$('.classCorporal').
 attr('data-bvalidator-msg', 'Seleccione al menos una opcion');             

    //Opciones del validador
    var optionsRed = { 
        classNamePrefix: 'bvalidator_bootstraprt_', 
        lang: 'es'
    };
 
    //Validar el formulario
    $('form').bValidator(optionsRed);
	
 }	
	