
 function validarProducto(){
$('.categoriaProducto').
        attr('data-bvalidator', 'required,required');
 
$('.nombreProducto').
        attr('data-bvalidator', 'required,required');

$('.costoProducto').
        attr('data-bvalidator', 'number,required,required');

$('.cantidadProducto').
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
	
 }	
	