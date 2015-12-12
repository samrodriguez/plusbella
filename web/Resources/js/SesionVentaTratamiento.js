
function validarSesionVentaTratamiento(){
$('.sucursalTratamiento').
        attr('data-bvalidator', 'required,required');
$('.sesionEmpleado').
        
        attr('data-bvalidator', 'required,required');
$('.fotoAntes').
        attr('data-bvalidator', 'extension[jpg:png],required');  
  
$('.fotoDespues').
  attr('data-bvalidator', 'extension[jpg:png],required');    
 
    //Opciones del validador
    var optionsRed = { 
        classNamePrefix: 'bvalidator_bootstraprt_', 
        lang: 'es'
    };
 
    //Validar el formulario
    $('form').bValidator(optionsRed);
	
 }	
	