//    var arrayCarreras;
    //////////////////////////////////////////////////////////cargar select con AJAX/////////////////////////////////////////////////////////////////////
    /*function cargarCarreras() {//parametro id select
     	$.ajax({
             method: "GET",
             url: "core/Controllers/CarreraController.php",
             data: {"opcion": 1}
     	}).done(function (info) { //informacion que el servlet le reenvia al jsp
            var datos = JSON.parse(info);
            arrayCarreras = datos.data;
        }).fail(function () {
                var data ={mensaje : "ERROR"};
                var id_form ="";
                verResultado(data, id_form);
            });
    }*/
    ////////////////////////////////////////////////FORM PRINCIPAL////////////////////////////////////////////////////////////
    jQuery(document).ready(function() {
        
    	/****************************************************************
    	* Contact form
    	****************************************************************/
//    	$('.contact-form div[role=form] input, .contact-form div[role=form] textarea').on('focus', function() {
//    		$('.contact-form div[role=form] input, .contact-form div[role=form] textarea').removeClass('contact-error');
//    	});
//    	enableFields();//activar fields disableds por checkbox
    	registerForm();//enviar datos
    	
    	/*******************************************************************
    	 * Cargar selects
    	*****************************************************************/
    	
                var controller = "SectorEconomicoController.php";
                cargarSelect("select[name=sectorEconomico]", controller, function (datos) {
                    $(datos).each(function (i, v) {
                        $("select[name=sectorEconomico]").append('<option value="' + v.sectorEconomicoId + '">' + v.nombreSectorEc + '</option>');
                    });
                });
            
      		/***************************************
      		 * Cambiar carreras cuando se cambie la universidad
      		**************************************************/
//      		$("select[name=universidad]").change(function(){//cuando se elija otra opcion del select
//        		var universidadID = $("select[name=universidad]").val();
//        		$("select[name=carrera]").empty();
//                $("select[name=carrera]").append("<option value=''>--Seleccione--</option>");
//        		$(arrayCarreras).each(function(i, v) {
//        		    if(v.universidadID == universidadID)
//                        $("select[name=carrera]").append('<option value="' + v.carreraID + '">'+ v.nombreC +'</option>');
//          		});
//        	});
//    	});
    	
    });
/////////////////////////////////////////////////////////enviar datos por ajax para cualquier formulario///////////////////////////////////////////////
    function registerForm(){
        $( "form[role=form]" ).submit(function( event ) {
            event.preventDefault();
            var id_form = $(this).attr("id");
            var data = new FormData();
//            if($(this).find('input[type=file]').length > 0){
//                jQuery.each($(this).find('input[type=file]')[0].files, function(i, file) {
//                    data.append('cv', file);
//                });
//                jQuery.each($(this).find('input[type=file]')[1].files, function(i, file) {
//                    data.append('cartaIntencion', file);
//                });
//            }
            var other_data = $(this).serializeArray();
            $.each(other_data,function(key,input){
                data.append(input.name,input.value);
            });
            $.ajax({
                url: 'core/Controllers/'+$(this).find("input#opcion").val()+'Controller.php',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                mimeType: "multipart/form-data",
                type: 'POST'
            }).done(function (info) { //informacion que el servlet le reenvia al jsp
                console.log(info);
                data = JSON.parse(info);
                verResultado(data, id_form);
            }).fail(function () {
                var data ={mensaje : "ERROR"};
                verResultado(data, id_form);
            });
        });
    }
/////////////////////////////////////////////////habilitar field con checkbox////////////////////////////////////////////////////////////////
//    function enableFields() {
//        var check = $( "form[role=form] input[type=checkbox]" ).change(function(){
//            var ch = $(this).is(':checked') ? true : false;
//            if(ch)
//                $(this).closest("form").find(".dis").removeAttr('disabled');
//            else
//                $(this).closest("form").find(".dis").attr('disabled', 'disabled');
//        });
//    }
/////////////////////////////////////////////////ver resultado del AJAX//////////////////////////////////////////////////////////////////////
    var verResultado = function(r, f) {//parametro(resultado-String)
        console.log(f);
    	if(r.mensaje == "CORRECTO"){
    	    $("#"+f).fadeOut('fast', function() {
                $(this).parent().append('<p style="font-weight:600; font-size:2em;">'+
                    '!Gracias por registrarte! Nos pondremos en contacto contigo proximamente =)</p>');
            });
     	}else if(r.mensaje == "INCORRECTO"){
     		mostrarMensaje("#dialogoD", "INCORRECTO", "<p>Los datos que has escrito son incorrectos vuelver a intentar</p>", "danger");
     		$("#"+f).find('input, textarea, select').addClass('contact-error animated shake').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
    			$(this).removeClass('animated shake');
    		});
     	}else if(r.mensaje =="VACIO"){
     	    mostrarMensaje("#dialogoD", "VACIO", "<p>No se especificó el tipo de participante, no edite el HTML</p>", "warning");
     		$("#"+f).find('input, textarea, select').addClass('contact-error animated shake').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
    			$(this).removeClass('animated shake');
    		});
    	}else if(r.mensaje =="ERROR"){
     	    mostrarMensaje("#dialogoD", "ERROR", "<P>Ha ocurrido un error inesperado durante el registro, esperamos su comprensión</p>", "danger");
    	}
    }
    
//mostrarMensaje("#dialog", "NO ENCONTRADO", "No se encontró el boton de limpiar, no modifique el HTML", "warning");
/*$('.contact-form form#form_donante').submit(function(e) {
		e.preventDefault();
	    $('.contact-form form input, .contact-form form textarea').removeClass('contact-error');
	    var postdata = $('.contact-form form#form_donante').serialize();
	    $.ajax({
	        type: 'POST',
	        url: 'Views/assets/contact.php',
	        data: postdata,
	        dataType: 'json',
	        success: function(json) {
	            if(json.emailMessage != '') {
	                $('.contact-form form#form_donante .contact-email').addClass('contact-error animated shake').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
            			$(this).removeClass('animated shake');
            		});
	            }
	            if(json.subjectMessage != '') {
	                $('.contact-form form#form_donante .contact-subject').addClass('contact-error animated shake').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
            			$(this).removeClass('animated shake');
            		});
	            }
	            if(json.messageMessage != '') {
	                $('.contact-form form#form_donante textarea').addClass('contact-error animated shake').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
            			$(this).removeClass('animated shake');
            		});
	            }
	            if(json.emailMessage == '' && json.subjectMessage == '' && json.messageMessage == '') {
	                $('.contact-form form#form_donante').fadeOut('fast', function() {
	                    $('#registro_donante').append('<p>Thanks for contacting us! We will get back to you very soon.</p>');
	                });
	            }
	        }
	    });
	});
	function clearFormEstudiante() {
        $("div[role=form]#form_Estudiante input, div[role=form]#form_Estudiante textarea").val("");
        var api1 = $.fileuploader.getInstance("#cv");
        var api2 = $.fileuploader.getInstance("#cartaIntencion");
        api1.reset();
        api1.reset();
        $('div[role=form]#form_Estudiante select').val($('div[role=form]#form_Estudiante select > option:first').val()).change();
    }*/