
        function validarSucursal(){
        $('.nombreSucursal').
        attr('data-bvalidator', 'required,required');
 
        $('.direccionSucursal').
        attr('data-bvalidator', 'required,required');
		
	$('.telefonoSucursal').
        attr('data-bvalidator', 'minlength[9],required,required');	
		
	$('.slugSucursal').
        attr('data-bvalidator', 'required,required');		
 
        //Opciones del validador
        var optionsRed = { 
            classNamePrefix: 'bvalidator_bootstraprt_', 
            lang: 'es'
        };

        //Validar el formulario
        $('form').bValidator(optionsRed);
    }
