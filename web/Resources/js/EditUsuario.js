
function validarEditUsuario(){
$('.empleadoUsuario').
        attr('data-bvalidator', 'required,required');    
    
$('.rolUsuario > div > label > input').
        attr('data-bvalidator', 'required,required');

$('.nombreUsuario').
        attr('data-bvalidator', 'required,required');

$('.firstPassword').
        attr('data-bvalidator');

$('.secondPassword').
        attr('data-bvalidator', 'equalto[firstPassword]');

$('.secondPassword').
        attr('data-bvalidator-msg', 'Las contraseñas deben coincidir, vuelva a digitarla');        

    //Opciones del validador
    var optionsRed = { 
        classNamePrefix: 'bvalidator_bootstraprt_', 
        lang: 'es'
    };
 
    //Validar el formulario
    $('form').bValidator(optionsRed);
	
 }	
	