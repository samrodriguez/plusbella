
 function validarCierre(){
     
$('.busquedaEmp').
  attr('data-bvalidator', 'required,required');
  
$('.fecha1').
  attr('data-bvalidator', 'required,required');
  
$('.motivoCierre').
  attr('data-bvalidator', 'required,required');  
  

 
    //Opciones del validador
    var optionsRed = { 
        classNamePrefix: 'bvalidator_bootstraprt_', 
        lang: 'es'
    };
 
    //Validar el formulario
    $('form').bValidator(optionsRed);
    
    //$('.fotoEmpleado').bValidator().css('position','absolute');
    //$('.fotoEmpleado').bValidator().css('width','50px');
    //$('.btn-file').css('width','200px');
	
 }	
	
