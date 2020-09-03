<?php
	require_once("../../core/Entities/Usuario.php");
	use Entities\Usuario;
	use Entities\Rol;
	session_set_cookie_params(0);
	session_cache_limiter('nocache');
	$cache_limiter = session_cache_limiter();
	session_cache_expire(0);
	$cache_expire = session_cache_expire();
	session_start();
	if (!isset($_SESSION['usuario'])) {
		header("location:/");
	}else{
		$usuario = unserialize($_SESSION['usuario']);
		if (!$usuario instanceof Usuario) {
		header("location:/");
		}else{
			$id = (int) $usuario->m_Rol->getRolID();
			if($usuario->m_Rol->getnombreRol() != "admin" && $id != 1)
				header("location:/");
		}
	}
?>
<!--elim dialog-->
<div id="dialogElim" style="position:absolute; z-index:5000;"></div>
<!--///////////////////////div donde se muestra un Dialogo/////////////////////////////// -->
<div id="formularios">
	<!-- Modal del area -->
	<div class="modal fade modalArea" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header success">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"><i class="fa fa-pencil-square" aria-hidden="true"></i> Formulario de Áreas de desempeño</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<form class="form-horizontal" role="form" id="formArea" method="POST">
							<input id="opcion" name="opcion" value="POST" type="hidden">
							<input id="areaDesempenioID" name="areaDesempenioID" value="" type="hidden">
							<div class="form-group">
								<label class="col-sm-4 control-label text-info">Nombre del área</label>
								<div class="col-sm-5">
									<input id="nombreArea" name="nombreArea" class="form-control" autofocus="" title="" data-original-title="Requerido" 
										focus="" type="text" required maxlength="40">
								</div>
							</div>
							<div class="form-group">
								<div style="" class="col-md-offset-3 col-md-3 text-center">
									<button id="btnEnviar" type="submit" class="btn btn-primary" style="">
										<span><i class="fa fa-save"></i></span> Guardar
									</button>
								</div>
								<div style="" class="col-md-3 text-center">
									<button type="button" class="btn btn-default" onclick="cancelar();" data-dismiss="modal">
										<span class="text-danger"><i class="fa fa-reply txt-danger"></i></span> Cancelar
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--///////////////////////Directorios donde estan los jsp /////////////////////////////// -->
<div class="row">
	<div id="breadcrumb" class="col-md-12">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="?view=Gestor">Principal</a></li>
			<li class="breadcrumb-item active">Catálogos</li>
			<li class="breadcrumb-item active">Áreas de desempeño</li>
		</ol>
	</div>
</div>
<!--///////////////////////DataTable de las areas de desempeño/////////////////////////////// -->
<div class="row">
	<div class="col-xs-12">
		<!-- tabla areas de desempeño -->
		<div class="panel panel-primary wow fadeInLeft animated">
			<div class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-list-alt" aria-hidden="true"></i> Lista de Áreas de desempeño</h2>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="table-responsive">
						<table class="table no-padding table-bordered table-striped table-hover table-heading table-datatable"
							id="tabla_area" style="width:100%;">
							<thead>
								<tr>
									<th>Nombre del Área de desempeño</th>
									<th>Acción</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
	
<script type="text/javascript">
////////////////////////////Funsión para cargar los plugin de botones de dataTables y listar la tabla////////////////
	function AllTables() {
		$.getScript('Views/assets/dataTables/jszip.min.js', function(){
			iniciarTabla();
		});
	}
	
	var limpiar_texto = function() {///////////////////////////////limpiar texto del formulario
		$("#opcion").val("POST");
		$("#areaDesempenioID").val("");
		$("#nombreArea").val("");
	}
///////////////////////////////Ejecutar el metodo DataTable para llenar la Tabla////////////////////////////////
	function iniciarTabla(){
		var tablaArea = $('#tabla_area').DataTable( {
			"destroy": true,
			'bProcessing': false,
			'bServerSide': false,
			"ajax": {
				"method":"GET",
				"data":{opcion:1},
				"url":"core/Controllers/AreaDesempenioController.php"
			},
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todo"]],
        	"bJQueryUI": true,	
			"language":idioma_esp,
			drawCallback: function(settings){
	            var api = this.api();
	            $("a.btn").tooltip({container: 'body'});
	            $("button.btn").tooltip({container: 'body'});
	            $(api.table().container()).find("div.btn-group").removeClass( "btn-group" );
	        },
			"columns": [
	            {"data":"nombreArea", "width": "65%"},
	            {"data":null,"defaultContent":"<button type='button' class='editarArea btn btn-primary' data-toggle='tooltip' "+
					"data-placement='top' title='Editar área de desempeño'>"+
					"<i class='fa fa-pencil-square-o'></i> </button>  "+
					"<button type='button' class='eliminarArea btn btn-danger' title='Eliminar área de desempeño'>"+
					"<i class='fa fa-trash-o'></i>"+
					"</button>" , "width": "35%"}],
            "dom":"<rt><'row'<'form-inline' <'col-sm-12 text-center'B>>>"
				 +"<'row' <'form-inline' <'col-sm-6'l><'col-sm-6'f>>>"
				 +"<rt>"
				 +"<'row'<'form-inline'"
				 +"<'col-sm-6 col-md-6 col-lg-6'i><'col-sm-6 col-md-6 col-lg-6'p>>>",
        	"buttons":[{
				"text": "<i class='fa fa-plus-square'></i>",
				"titleAttr": "Agregar Área de desempeño",
				"className": "btn btn-success",
				"action": function() {
					agregar_nuevo_registro();
				}},
	            {
	                extend:    'excel',
	                text:      '<i class="fa fa-table" aria-hidden="true"></i>',
	                titleAttr: 'Excel'
	            }]
		});
		obtener_datos_editar('#tabla_area tbody', tablaArea);
		obtener_id_eliminar('#tabla_area tbody', tablaArea);
		$('.cargador').css({visibility: "hidden", display: "none" });
		$('#contenidoPaginas').show();
	}
	var agregar_nuevo_registro = function() {//////////////agregar nuevo registro limpiando texto y abriendo el form
		limpiar_texto();
		console.log("nuevo registro");
		$('.modalArea').modal({
			backdrop: 'static',
			show: true,
			keyboard: false
		});
		$('.modalArea').on('shown.bs.modal', function (e) {
			$("#formArea input#nombreArea").focus();
		});
	}
	
	var cancelar = function() {////////////////cancela la acción limpiando el texto
		limpiar_texto();
		console.log("cancelar acción");
	}
///////////////////////////funsión que activa el evento click del boton editar del dataTable////////////////////
	var obtener_datos_editar = function(tbody, table) {//parametro(id_tabla, objeto dataTable)
		$(tbody).on("click", "button.editarArea", function() {
			var datos = table.row($(this).parents("tr")).data();
			$("#formArea input#nombreArea").val(datos.nombreArea);
			$("#formArea input#areaDesempenioID").val(datos.areaDesempenioID);
			$("#formArea input#opcion").val("PUT");
			console.log("actualizar");
			$('.modalArea').modal({
				backdrop: true,
				show: true,
				keyboard: false
			});
			$('.modalArea').on('shown.bs.modal', function (e) {
				$("#formArea input#nombreArea").focus();
			});
		});
	}
/////////////////////////funsión que activa el evento click para eliminar un registro del dataTable////////////////
	var obtener_id_eliminar = function(tbody, table) {//parametro(id_tabla, objeto dataTable)
		$(tbody).on("click", "button.eliminarArea", function() {
			var datos = table.row($(this).parents("tr")).data();
			formElim =	"<p Style='text-align:center; color:salmon; font-size: large;'>¿Desea borrar esta área de desempeño?</p>"+
						"<form id='frmEliminarArea' action='' method='DELETE'>"+
							"<input type='hidden' id='areaDesempenioID' name='areaDesempenioID' value=''>"+
							"<input type='hidden' id='opcion' name='opcion' value='DELETE'>"+
							"<div class='text-center'> "+
							"<button type='submit' id='eliminar_area' class='btn btn-danger btn-label-left'"+
							" style='color:#ece1e1;'>"+"<span><i class='fa fa-trash-o'></i></span>Eliminar</button>"+
						"</form>";
			mostrarMensaje("#dialogElim", "Eliminar área de desempeño", formElim, "danger");
			$('#dialogElim #miModal').on('shown.bs.modal', function (e) {
				$("#frmEliminarArea input#areaDesempenioID").val(datos.areaDesempenioID);
				$("#frmEliminarArea input#opcion").val("DELETE");
			});
			console.log("eliminar");
			guardarAccion("form#frmEliminarArea");
		});
	}
//////////////////////////////////guardar los datos seteados en el formulario/////////////////////////////////////
	var guardarAccion = function(form) {
		$(form).on("submit", function(ev) {
			ev.preventDefault();
			frm = $(this).serialize();
			var id_form = $(this).attr("id");
			var opcion = $(this).find("input#opcion").val();
			console.log("opcion: "+opcion+", form: "+id_form+"\n"+frm);
			if(id_form == "frmEliminarArea")
				$('#dialogElim #miModal').modal('hide');
			$.ajax({
				method:opcion,
				url:"core/Controllers/AreaDesempenioController.php",
				data: frm
			}).done(function(info) {
				console.log(decodeURIComponent(escape(info)));
				var m = JSON.parse(info);
				if(m.titulo == "CORRECTO"){
					m.mensaje = decodeURIComponent(escape(m.mensaje));
					limpiar_texto();
					$('.modalArea').modal('hide');
					$('#tabla_area').DataTable().ajax.reload();
				}
				mostrarMensaje("#dialogM", m.titulo, m.mensaje, m.tipo);
			}).fail(function () {
                mostrarMensaje("#dialogM", "ERROR", "¡No se logró realizar la acción!", "danger");
				limpiar_texto();
				$('.modalArea').modal('hide');
            });
		});
	}
/////////////////////////////////////////////////FUNSIÓN PRINCIPAL/////////////////////////////////////////////////
	$(document).ready(function() {
		//cargar scripts dataTables
		LoadDataTablesScripts(AllTables);
		
		// Añadir Tooltip para formularios
		$('.form-control').tooltip();
		//add tooltip
		$('[data-toggle="tooltip"]').tooltip();
		guardarAccion("form#formArea");
	});
	
</script>
