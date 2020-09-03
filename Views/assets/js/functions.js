/*********************************mostrar mensajes*************************************/
function mostrarMensaje(dialogo, titulo, contenido, tipo) {
	var dialog='<div id="miModal" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">'+
				  '<div class="modal-dialog modal-sm" role="document">'+
				  	'<div class="modal-content">'+
				  		'<div class="modal-header '+tipo+'">'+
							'<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
							'<h4 class="modal-title">'+titulo+'</h4>'+
						'</div>'+
						'<div class="modal-body text-center" style="font-size:larger;">'+contenido+'</div>'+
						'<div class="modal-footer" style="text-align: center;">'+
							'<button type="button" class="btn btn-default '+tipo+'" data-dismiss="modal" focus>Cerrar</button>'+
						'</div>'+
				    '</div>'+
				  '</div>'+
				'</div>';
	$(dialogo).append(dialog);
	$(dialogo+' #miModal').modal({
		backdrop: true,
		show: true,
		keyboard: true
	});
	$(dialogo+' #miModal').on('hidden.bs.modal', function (e) {
		$(this).remove();
	});
}

////////////////////////////////////////////////////fucntion to give style to inputs File///////////////////////////////////////////////////
function doInputFile(inputs){
	$(inputs).fileuploader({
			maxSize: 0.5, // tamaño máximo en MB
			enableApi: true,
			afterRender: function(listEl, parentEl, newInputEl, inputEl) {
				$(inputEl).parent().find('.fileuploader-input .fileuploader-input-button').on('click',function() {
					$(inputEl).after("<p id='pload' style='text-align:center;'>CARGANDO...</p>");
				});
			},
			afterSelect: function(listEl, parentEl, newInputEl, inputEl) {
				$(inputEl).parent().find('#pload').remove();
			},
			captions: {
	            button: function(options) { return 'Escoger ' + (options.limit == 1 ? 'Archivo' : 'Archivos'); },
	            feedback: function(options) { return 'Escoger ' + (options.limit == 1 ? 'Archivo' : 'Archivos') + ' para Cargar'; },
	            feedback2: function(options) { return options.length + ' ' + (options.length > 1 ? ' Archivos que fueron seleccionados' : ' Archivo que fue seleccionado'); },
	            drop: 'Arrastra los Archivos Aqui para cargarlos',
	            paste: '<div class="fileuploader-pending-loader"><div class="left-half" style="animation-duration: ${ms}s"></div><div class="spinner" style="animation-duration: ${ms}s"></div><div class="right-half" style="animation-duration: ${ms}s"></div></div> Pega un Archivo, Click Aqui para Cancelar.',
	            removeConfirmation: '¿Estas seguro de remover este archivo?',
	            errors: {
	                filesLimit: 'Solamente ${limit} Archivos pueden cargarse.',
	                filesType: 'Solamente ${extensions} Archivos pueden cargarse.',
	                fileSize: '${name} ¡Archivo extenso! Porfavor, escoge un archivo de menos de ${fileMaxSize}MB.',
	                filesSizeAll: '¡Los archivos que escogistes son muy extensos! Porfavor, escoge archivos de menos de ${maxSize} MB.',
	                fileName: 'El archivo con el nombre de ${name} ya fue seleccionado.',
	                folderUpload: 'No esta permitido subir carpetas.'
	            }
        	}
		});
}

//////////////////////////////////////////////////////////cargar select con AJAX/////////////////////////////////////////////////////////////////////
    function cargarSelect(select, Controller, f) {//parametro id select
     	$.ajax({
             method: "GET",
             url: "core/Controllers/"+Controller,
             data: {"opcion": 1}
     	}).done(function (info) { //informacion que el servlet le reenvia al jsp
            var datos = JSON.parse(info);
            var lista = datos.data;
            $(select).empty();
            $(select).append("<option value=''>--Seleccione--</option>");
            f(lista);
        }).fail(function () {
                var data ={mensaje : "ERROR"};
                var id_form ="";
                verResultado(data, id_form);
        });
    }