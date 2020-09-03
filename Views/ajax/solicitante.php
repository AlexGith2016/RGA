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
    <!-- Modal de solicitantes -->
    <div class="modal fade modalSolicitante" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header success">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-pencil-square" aria-hidden="true"></i> Formulario de Solicitantes</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 contact-form">
                            <form role="form" id="formSolicitante">
                                <input id="solicitanteID" name="solicitanteID" value="" type="hidden">
                                <!--Nombre Completo-->
                                <div class="form-group">
                                    <label for="nombreCompleto">Nombre Completo:</label>
                                    <input type="text" name="nombreCompleto" placeholder="Nombre del solicitante..." 
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
                                <!--identificacion-->
                                <div class="form-group">
                                    <label for="identificacion">Identificación:</label>
                                    <input type="text" name="identificacion" placeholder="Identificación..." 
                                           class="pais" required maxlength="30" readonly>
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
			<li class="breadcrumb-item active">Solicitantes</li>
			<li class="breadcrumb-item active">Solicitante</li>
		</ol>
	</div>
</div>
<!--///////////////////////DataTable de los solicitante/////////////////////////////////// -->
<div class="row">
    <div class="col-xs-12">
        <!-- tabla solicitantes -->
        <div class="panel panel-primary wow fadeInLeft animated">
            <div class="panel-heading">
                <h2 class="panel-title"><i class="fa fa-list-alt" aria-hidden="true"></i> Lista de Solicitantes</h2>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table no-padding table-bordered table-striped table-hover table-heading table-datatable"
                               id="tabla_solicitante" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>Nombre completo</th>
                                    <th>Correo</th>
                                    <th>Teléfono</th>
                                    <th>Fecha de registro</th>
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
		$("#formSolicitante input[name=solicitanteID]").val("");
		$("#formSolicitante input[name=nombreCompleto]").val("");
		$("#formSolicitante input[name=correo]").val("");
		$("#formSolicitante input[name=telefono]").val("");
		$("#formSolicitante input[name=identificacion]").val("");
	}
///////////////////////////////Ejecutar el metodo DataTable para llenar la Tabla////////////////////////////////
    function iniciarTabla(){
        var tablaSolicitante = $('#tabla_solicitante').DataTable( {
            "destroy": true,
            'bProcessing': false,
            'bServerSide': false,
            "ajax": {
                    "method":"GET",
                    "data":{opcion:1,activos:1},
                    "url":"core/Controllers/SolicitanteController.php"
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
//            { "data": null,
//                render: function ( data, type, row ) {
//                        if(data.organizacion == null || data.organizacion == "")
//                                return "N/A";
//                        else
//                                return data.organizacion;
//                        }, "width": "16%"},
                {"data":null,"defaultContent":"<button type='button' class='visualizarD btn btn-info' data-toggle='tooltip' "+
                    "data-placement='top' title='Visualizar solicitante'>"+
                        "<i class='fa fa-info-circle' aria-hidden='true'></i> </button>  "+
                    "<button type='button' class='imprimirFicha btn btn-warning' data-toggle='tooltip' "+
                            "data-placement='top' title='imprimir ficha de participación'>"+
                            "<i class='fa fa-print' aria-hidden='true'></i> </button>", "width": "8%"}
            ],
            "dom":"<rt><'row'<'form-inline' <'col-sm-12 text-center'B>>>"
                +"<'row' <'form-inline' <'col-sm-6'l><'col-sm-6'f>>>"
                +"<rt>"
                +"<'row'<'form-inline'"
                +"<'col-sm-6 col-md-6 col-lg-6'i><'col-sm-6 col-md-6 col-lg-6'p>>>",
            "buttons":[{
                "text": "<i class='fa fa-list-alt text-muted' aria-hidden='true'></i>",
                "titleAttr": "imprimir lista solicitantes",
                "className": "btn btn-success",
                "action": function() {
                        imprimirLista(tablaSolicitante);
                }},
                {
                extend:    'excel',
                text:      '<i class="fa fa-table" aria-hidden="true"></i>',
                titleAttr: 'Excel'
            }]
        });
        obtener_datos_visualizar('#tabla_solicitante tbody', tablaSolicitante);
        imprimirFicha('#tabla_solicitante tbody', tablaSolicitante);
        $('.cargador').css({visibility: "hidden", display: "none" });
        $('#contenidoPaginas').show();
    }
    
    var imprimirLista = function(table) {//////////////imprimir lista de solicitantes
            console.log("imprimir lista de solicitantes");
            window.open("/core/Controllers/SolicitanteController.php/?opcion=2","Lista de solicitantes - CIA");
    }

///////////////////////////funsión que activa el evento click del boton imprimir ficha del dataTable////////////////////
    var imprimirFicha = function(tbody, table) {//parametro(id_tabla, objeto dataTable)
        $(tbody).on("click", "button.imprimirFicha", function() {
            var datos = table.row($(this).parents("tr")).data();
            console.log("imprimirFicha");
            var id = datos.solicitanteID;
            imprimirF =	"<p Style='text-align:center; color:black; font-size: large;'>¿Desea imprimir la ficha de "+
                    "informe de este solicitante?</p>"+
                    "<div class='text-center' style='width:100%;'>"+
                    "<input id='idSolicitante' type='hidden' value='"+id+"'/>"+
                    "<button type='button' id='btnImprimirF' class='btn btn-info btn-label-left'"+
                    " style='color:#ece1e1;' data-dismiss='modal'>"+"<span><i class='fa fa-print' aria-hidden='true'></i></span>Imprimir</button></div>";
            mostrarMensaje("#dialogM", "Imprimir ficha de participación", imprimirF, "info");
            realizarAccion(id);
        });
    }
///////////////////////////funsión que activa el evento click del boton visualizar del dataTable////////////////////
    var obtener_datos_visualizar = function(tbody, table) {//parametro(id_tabla, objeto dataTable)
        $(tbody).on("click", "button.visualizarD", function() {
            var datos = table.row($(this).parents("tr")).data();
            $("#formSolicitante input[name=solicitanteID]").val(datos.solicitanteID);
            $("#formSolicitante input[name=nombreCompleto]").val(datos.nombreCompleto);
            $("#formSolicitante input[name=correo]").val(datos.correo);
            $("#formSolicitante input[name=telefono]").val(datos.telefono);
            $("#formSolicitante input[name=identificacion]").val(datos.identificacion);
            console.log("visualizar");
            $('.modalSolicitante').modal({
                backdrop: true,
                show: true,
                keyboard: false
            });
        });
    }
//////////////////////////////////imprimir registro selccionado/////////////////////////////////////
    var realizarAccion = function() {
        $("#btnImprimirF").on("click", function(ev) {
            ev.preventDefault();
            var idS= $("input#idSolicitante").val();
            console.log("realizar Acción de imprimir ficha");
            window.open("/core/Controllers/SolicitanteController.php/?opcion=3&id="+idS,"ficha de participación de Solicitante - CIA");
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
    });
	
</script>
