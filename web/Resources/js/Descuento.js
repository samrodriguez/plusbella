
$(document).ready(function(){
$('#dgplusbellebundle_descuento_nombre').
        attr('data-bvalidator', 'required,required');
 
    $('#dgplusbellebundle_descuento_porcentaje').
        attr('data-bvalidator', 'rangelength[1:5],between[0:100],required,required');

    //Opciones del validador
    var optionsRed = { 
        classNamePrefix: 'bvalidator_bootstraprt_', 
        lang: 'es'
    };
 
    //Validar el formulario
    $('form').bValidator(optionsRed);
	
 });//Fin document ready	
	