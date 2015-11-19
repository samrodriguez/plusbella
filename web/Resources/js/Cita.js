
$(document).ready(function(){
$('#dgplusbellebundle_ventapaquete_empleado').
attr('data-bvalidator', 'required,required');

$('#dgplusbellebundle_ventapaquete_paquete').
attr('data-bvalidator', 'required,required');

$('#dgplusbellebundle_ventapaquete_fechaVenta').
attr('data-bvalidator', 'required,required');
	
 
    //Opciones del validador
    var optionsRed = { 
        classNamePrefix: 'bvalidator_bootstraprt_', 
        onBeforeValidate: function(element, action){
            
            
            var idEmpleado = $('#dgplusbellebundle_cita_empleado').val();
            var fechaCita= $('#dgplusbellebundle_cita_fechaCita').val();
            var horaCita = $('#dgplusbellebundle_cita_horaCita_hour').val()+":"+$('#dgplusbellebundle_cita_horaCita_minute').val();
                    //alert(idEmpleado);
            $.getJSON(Routing.generate('get_existeCita', { idempleado: idEmpleado,fecha:fechaCita,hora:horaCita}), 
                function(data) {
                    if(data.regs.length!=0){
                        var mensaje = "";
                        mensaje += "<div class='row'><div class='col-md-12'><b><p class='text-left'>Error...</p></b></div></div>";
                        mensaje += "<div class='row'><hr></div>";
                        mensaje += "<div class='row'><div class='col-md-12'>Ya existe una cita programada para ese doctor en ese dia y esa hora</div></div>"
                        bootbox.alert(mensaje, function() {

                        });
                    }
            });
                        
        },
        lang: 'es'
    };
 
    //Validar el formulario
    $('form').bValidator(optionsRed);
	
 });//Fin document ready	
	