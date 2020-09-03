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
<!--///////////////////////div donde se muestra un Dialogo/////////////////////////////// -->
<div id="formularios">
	<!-- Modal de donantes -->
	<div class="modal fade modalEmpresa" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header success">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"><i class="fa fa-pencil-square" aria-hidden="true"></i> Formulario de Empresas</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div  class="col-sm-12 contact-form wow fadeInLeft">
				            <!--*********************formularios de empresas*******************************-->
				            <form role="form" id="formEmpresa">
				            	<input id="donanteID" name="donanteID" value="" type="hidden">
				               	<!--Nombre Completo-->
				               	<div class="form-group">
				                	<label for="nombreCompleto">Nombre Completo del responsable:</label>
				                   	<input type="text" name="nombreCompleto" placeholder="Nombre del responsable..." 
				                   	class="nombreCompleto" required autofocus maxlength="200" readonly>
								</div>
								<!--Correo-->
				               	<div class="form-group">
				               		<label for="correo">Correo Electrónico:</label>
				                  	<input type="email" name="correo" placeholder="Correo..." class="correo" 
				                  		required maxlength="90" readonly>
								</div>
								<!--Telefono-->
								<div class="form-group">
				                	<label for="telefono">Teléfono de contacto:</label>
				                   	<input type="number" name="telefono" placeholder="Teléfono o celular..."
				                   	class="telefono" id="telefono" required min="11111111" max="9999999999" readonly>
								</div>
								<!--Nombre empresa-->
				               	<div class="form-group">
				                	<label for="nombreEmpresa">Nombre de la Empresa:</label>
				                   	<input type="text" name="nombreEmpresa" placeholder="Empresa..." 
				                   	class="nombreEmpresa" required maxlength="70" readonly>
								</div>
								<!--cuidad-->
								<div class="form-group">
				                	<label for="cuidadDep">Cuidad o Departamento:</label>
				                    <select name="cuidadDep" id="cuidadDep select" 
				                    	class="form-control" required readonly>
				                    	<option value="">--Seleccione--</option>
									  	<option value="1">Managua</option>
									  	<option value="2">Masaya</option>
										<option value="3">León</option>
										<option value="4">Granada</option>
										<option value="5">Carazo</option>
										<option value="6">Estelí</option>
										<option value="7">Rivas</option>
										<option value="8">Chinandega</option>
										<option value="9">Chontales</option>
										<option value="10">Madriz</option>
										<option value="11">Matagalpa</option>
										<option value="12">Nueva Segovia</option>
										<option value="13">Boaco</option>
										<option value="14">Río San Juan</option>
										<option value="15">Jinotega</option>
										<option value="16">Atlántico Sur (RAAS)</option>
										<option value="17">Atlántico Norte (RAAN)</option>
									</select>
								</div>
								<!--direccionEmpresa-->
								<div class="form-group">
				                	<label for="direccion">Dirección:</label>
				                    <textarea name="direccion" placeholder="Dirección..." 
				                    	class="direccion" id="direccion"
				                    	maxlength="200" required readonly></textarea>
								</div>
								<!--numero de empleados-->
								<div class="form-group">
				                	<label for="numeroEmpleados">Cantidad de empleados:</label>
				                   	<input type="number" name="numeroEmpleados" placeholder="Número de empleados..."
				                   	class="numeroEmpleados" id="numeroEmpleados" required min="1" max="999999999" readonly>
								</div>
								<!--Cargo-->
				               	<div class="form-group">
				                	<label for="cargo">Cargo que posee:</label>
				                   	<input type="text" name="cargo" placeholder="Cargo en la Empresa..." 
				                   	class="cargo" required maxlength="60" readonly>
								</div>
								<!--Año de constitución-->
								<div class="form-group">
				                	<label for="anioConstitucion">Digíte el año en que se constituyó de la Empresa:</label>
				                   	<input type="number" name="anioConstitucion" placeholder="Años de constitución..."
				                   	class="anioConstitucion" id="anioConstitucion" required min="1" max="4000" readonly>
								</div>
								<!--sector Económico-->
								<div class="form-group">
				                	<label for="sectorEconomico">Sector Económico:</label>
				                    <select name="sectorEconomico" id="sectorEconomico select" 
				                    	class="form-control" required readonly>
									  <option value="">--Seleccione--</option>
									</select>
								</div>
								<!--descripcion de empresa-->
								<div class="form-group">
				                	<label for="descripcionEmpresa">Breve descripción del negocio, los productos o servicios que ofrece (¿A qué se dedica?, ¿Quiénes son sus beneficiarios, clientes/ usuarios?):</label>
				                    <textarea name="descripcionEmpresa" placeholder="Descripción de la Empresa..." 
				                    	class="descripcionEmpresa" id="descripcionEmpresa"
				                    	maxlength="240" required readonly></textarea>
								</div>
								<!--descripcion de problema-->
								<div class="form-group">
				                	<label for="descripcionProblema">Descripción del problema. Preguntas: ¿Cuál es la problemática de su empresa?, ¿Quién lo tiene? , ¿Quiénes participan?:</label>
				                    <textarea name="descripcionProblema" placeholder="Descripción del problema..." 
				                    	class="descripcionProblema" id="descripcionProblema"
				                    	maxlength="250" required readonly></textarea>
								</div>
								<!--Solucion temporal-->
								<div class="form-group">
				                	<label for="solucionTemporal">Preguntas: ¿Hay alguna solución actual?, ¿Qué beneficios  se esperan?:</label>
				                    <textarea name="solucionTemporal" placeholder="Solución Temporal..." 
				                    	class="solucionTemporal" id="solucionTemporal"
				                    	maxlength="200" required readonly></textarea>
								</div>
								<!--plan de crecimiento-->
								<div class="form-group">
				                	<label for="planCrecimiento">Pregunta: ¿Qué le gustaría lograr con su empresa u organización? Cuéntenos sus planes de crecimiento o expansión para su negocio:</label>
				                    <textarea name="planCrecimiento" placeholder="Plan de Crecimiento, Campo no requerido..." 
				                    	class="planCrecimiento" id="planCrecimiento"
				                    	maxlength="200" readonly></textarea>
								</div>
								<!--Solucion temporal-->
								<div class="form-group">
				                	<label for="razonSeleccion">Pregunta: ¿Por qué debe ser seleccionada su empresa para participar en la Temporada de Innovación?:</label>
				                    <textarea name="razonSeleccion" placeholder="Razón..." 
				                    	class="razonSeleccion" id="razonSeleccion"
				                    	maxlength="200" required readonly></textarea>
								</div>
								<div class="form-group text-center">
									<div style="" class="col-md-offset-4 col-md-4 text-center">
										<button id="btnEnviar" type="button" class="btn btn-primary" data-dismiss="modal" onclick="limpiar_texto();">
											<span><i class="fa fa-check" aria-hidden="true"></i></span> Aceptar
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
</div>
<!--///////////////////////Directorios donde estan los jsp /////////////////////////////// -->
<div class="row">
	<div id="breadcrumb" class="col-md-12">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="?view=Gestor">Principal</a></li>
			<li class="breadcrumb-item active">Participantes</li>
			<li class="breadcrumb-item active">Empresas</li>
		</ol>
	</div>
</div>
<!--///////////////////////DataTable de las empresas/////////////////////////////////// -->
<div class="row">
	<div class="col-xs-12">
		<!-- tabla empresas -->
		<div class="panel panel-primary wow fadeInLeft animated">
			<div class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-list-alt" aria-hidden="true"></i> Lista de Empresas</h2>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="table-responsive">
						<table class="table no-padding table-bordered table-striped table-hover table-heading table-datatable"
							id="tabla_empresa" style="width:100%;">
							<thead>
								<tr>
									<th>Nombre completo</th>
									<th>Correo</th>
									<th>Teléfono</th>
									<th>Fecha de registro</th>
									<th>Empresa</th>
									<th>Cargo</th>
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
		console.log("limpiar texto");
		$("#formEmpresa input[name=donanteID]").val("");
		$("#formEmpresa input[name=nombreCompleto]").val("");
		$("#formEmpresa input[name=correo]").val("");
		$("#formEmpresa input[name=telefono]").val("");
		$("#formEmpresa input[name=nombreEmpresa]").val("");
		$("#formEmpresa select[name=cuidadDep]").val("").change();
		$("#formEmpresa textarea[name=direccion]").val("");
		$("#formEmpresa input[name=numeroEmpleados]").val("");
		$("#formEmpresa input[name=cargo]").val("");
		$("#formEmpresa input[name=anioConstitucion]").val("");
		$("#formEmpresa select[name=sectorEconomico]").val("").change();
		$("#formEmpresa textarea[name=descripcionEmpresa]").val("");
		$("#formEmpresa textarea[name=descripcionProblema]").val("");
		$("#formEmpresa textarea[name=solucionTemporal]").val("");
		$("#formEmpresa textarea[name=planCrecimiento]").val("");
		$("#formEmpresa textarea[name=razonSeleccion]").val("");
	}
///////////////////////////////Ejecutar el metodo DataTable para llenar la Tabla////////////////////////////////
	function iniciarTabla(){
		var tablaEmpresa = $('#tabla_empresa').DataTable( {
			"destroy": true,
			'bProcessing': false,
			'bServerSide': false,
			"ajax": {
				"method":"GET",
				"data":{opcion:1,activos:1},
				"url":"core/Controllers/EmpresaController.php"
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
	            {"data":"nombreCompleto", "width": "21%"},
	            {"data":"correo", "width": "17%"},
	            {"data":"telefono", "width": "11%"},
	            { "data": null,
	                render: function ( data, type, row ) {
	                	f = new Date(data.fechaRegistro);
	                	var fecha = f.getDate()+"/"+(f.getMonth()+1)+"/"+f.getFullYear();
	                	return fecha;
	            }, "width": "13%"},
	            {"data":"nombreEmpresa", "width": "16%"},
	            {"data":"cargo", "width": "14%"},
	            {"data":null,"defaultContent":"<button type='button' class='visualizarEm btn btn-info' data-toggle='tooltip' "+
					"data-placement='top' title='Visualizar empresa'>"+
					"<i class='fa fa-info-circle' aria-hidden='true'></i> </button>  "+
	            	"<button type='button' class='imprimirFicha btn btn-warning' data-toggle='tooltip' "+
					"data-placement='top' title='imprimir ficha de participación'>"+
					"<i class='fa fa-print' aria-hidden='true'></i> </button>", "width": "8%"}],
            "dom":"<rt><'row'<'form-inline' <'col-sm-12 text-center'B>>>"
				 +"<'row' <'form-inline' <'col-sm-6'l><'col-sm-6'f>>>"
				 +"<rt>"
				 +"<'row'<'form-inline'"
				 +"<'col-sm-6 col-md-6 col-lg-6'i><'col-sm-6 col-md-6 col-lg-6'p>>>",
        	"buttons":[{
				"text": "<i class='fa fa-list-alt text-muted' aria-hidden='true'></i>",
				"titleAttr": "imprimir lista de empresas",
				"className": "btn btn-success",
				"action": function() {
					imprimirLista(tablaEmpresa);
				}},
	            {
	                extend:    'excel',
	                text:      '<i class="fa fa-table" aria-hidden="true"></i>',
	                titleAttr: 'Excel'
	            }]
		});
		obtener_datos_visualizar('#tabla_empresa tbody', tablaEmpresa);
		imprimirFicha('#tabla_empresa tbody', tablaEmpresa);
		$('.cargador').css({visibility: "hidden", display: "none" });
		$('#contenidoPaginas').show();
	}
	var imprimirLista = function(table) {//////////////imprimir lista de donantes
		console.log("imprimir lista de empresas");
		window.open("/core/Controllers/EmpresaController.php/?opcion=2","Lista de empresas - CIA");
	}

///////////////////////////funsión que activa el evento click del boton imprimir ficha del dataTable////////////////////
	var imprimirFicha = function(tbody, table) {//parametro(id_tabla, objeto dataTable)
		$(tbody).on("click", "button.imprimirFicha", function() {
			var datos = table.row($(this).parents("tr")).data();
			console.log("imprimirFicha");
			var id = datos.empresaID;
			console.log("id: "+id);
			imprimirF =	"<p Style='text-align:center; color:black; font-size: large;'>¿Desea imprimir la ficha de "+
				"participación de esta empresa?</p>"+
				"<div class='text-center' style='width:100%;'>"+
				"<input id='idEmpresa' type='hidden' value='"+id+"'/>"+
				"<button type='button' id='btnImprimirF' class='btn btn-info btn-label-left'"+
				" style='color:#ece1e1;' data-dismiss='modal'>"+"<span><i class='fa fa-print' aria-hidden='true'></i></span>Imprimir</button></div>";
			mostrarMensaje("#dialogM", "Imprimir ficha de participación", imprimirF, "info");
			realizarAccion(id);
		});
	}
///////////////////////////funsión que activa el evento click del boton visualizar del dataTable////////////////////
	var obtener_datos_visualizar = function(tbody, table) {//parametro(id_tabla, objeto dataTable)
		$(tbody).on("click", "button.visualizarEm", function() {
			var datos = table.row($(this).parents("tr")).data();
			$("#formEmpresa input[name=donanteID]").val(datos.empresaID);
			$("#formEmpresa input[name=nombreCompleto]").val(datos.nombreCompleto);
			$("#formEmpresa input[name=correo]").val(datos.correo);
			$("#formEmpresa input[name=telefono]").val(datos.telefono);
			$("#formEmpresa input[name=nombreEmpresa]").val(datos.nombreEmpresa);
			$("#formEmpresa select[name=cuidadDep]").val(datos.cuidadDepID).change();
			$("#formEmpresa textarea[name=direccion]").val(datos.direccion);
			$("#formEmpresa input[name=numeroEmpleados]").val(datos.numeroEmpleados);
			$("#formEmpresa input[name=cargo]").val(datos.cargo);
			$("#formEmpresa input[name=anioConstitucion]").val(datos.anioConstitucion);
			$("#formEmpresa select[name=sectorEconomico]").val(datos.sectorEconomicoID).change();
			$("#formEmpresa textarea[name=descripcionEmpresa]").val(datos.descripcionEmpresa);
			$("#formEmpresa textarea[name=descripcionProblema]").val(datos.descripcionProblema);
			$("#formEmpresa textarea[name=solucionTemporal]").val(datos.solucionTemporal);
			if(datos.planCrecimiento == null || datos.planCrecimiento == "")
        		$("#formEmpresa textarea[name=planCrecimiento]").val("N/A");
        	else
        		$("#formEmpresa textarea[name=planCrecimiento]").val(datos.planCrecimiento);
			$("#formEmpresa textarea[name=razonSeleccion]").val(datos.razonSeleccion);
			console.log("visualizar");
			$('.modalEmpresa').modal({
				backdrop: true,
				show: true,
				keyboard: false
			});
		});
	}
//////////////////////////////////imprimir registro selccionado/////////////////////////////////////
	var realizarAccion = function(id) {
		$("#btnImprimirF").on("click", function(ev) {
			ev.preventDefault();
			var idE= $("input#idEmpresa").val();
			console.log("realizar Acción de imprimir ficha para empresas: "+idE+", id: "+id);
			window.open("/core/Controllers/EmpresaController.php/?opcion=3&id="+idE,"ficha de participación de empresas - CIA");
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
		
		cargarSelect("select[name=sectorEconomico]", "SectorEconomicoController.php", function(datos) {
	  	    $(datos).each(function(i, v) {
	  	        $("select[name=sectorEconomico]").append('<option value="' + v.sectorEconomicoID + '">'+ v.nombreSectorEc +'</option>');
	  	    });
		});
});
	
</script>
