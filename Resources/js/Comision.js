
 function validarComision(){
	
$('.descripcionComision').
        attr('data-bvalidator', 'required,required');
		
$('.porcentajeComision').
        attr('data-bvalidator', 'rangelength[1:5],between[0:100],required,required');

$('.metaComision').
        attr('data-bvalidator', 'number,required,required');		
	
$('.empleadoComision').
        attr('data-bvalidator', 'required,required');
 
    //Opciones del validador
    var optionsRed = { 
        classNamePrefix: 'bvalidator_bootstraprt_', 
        lang: 'es'
    };
 
    //Validar el formulario
    $('form').bValidator(optionsRed);
	
 }	
	