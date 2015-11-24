
$(document).ready(function(){
$('#dgplusbellebundle_producto_categoria').
        attr('data-bvalidator', 'required,required');
 
    $('#dgplusbellebundle_producto_nombre').
        attr('data-bvalidator', 'required,required');

     $('#dgplusbellebundle_producto_costo').
        attr('data-bvalidator', 'number,required,required');	

     $('#dgplusbellebundle_producto_fechaCompra').
        attr('data-bvalidator', 'required,required');	
     $('#dgplusbellebundle_producto_fechaVencimiento').
        attr('data-bvalidator', 'required,required');			

    //Opciones del validador
    var optionsRed = { 
        classNamePrefix: 'bvalidator_bootstraprt_', 
        lang: 'es'
    };
 
    //Validar el formulario
    $('form').bValidator(optionsRed);
	
 });//Fin document ready	
	