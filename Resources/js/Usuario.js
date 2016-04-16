
function validarUsuario(){
$('.empleadoUsuario').
        attr('data-bvalidator', 'required,required');    
    
$('.rolUsuario > div > label > input').
        attr('data-bvalidator', 'required,required');

$('.nombreUsuario').
        attr('data-bvalidator', 'required,required');

$('#dgplusbellebundle_usuario_password_first').
        attr('data-bvalidator', 'required,required');

$('#dgplusbellebundle_usuario_password_second').
        attr('data-bvalidator', 'equalto[dgplusbellebundle_usuario_password_first],required,required');
	
$('#dgplusbellebundle_usuario_password_second').
        attr('data-bvalidator-msg', 'Las contraseñas deben coincidir, vuelva a digitarla');        
 
    //Opciones del validador
    var optionsRed = { 
        classNamePrefix: 'bvalidator_bootstraprt_', 
        lang: 'es'
    };
 
    //Validar el formulario
    $('form').bValidator(optionsRed);
	
 }	
	