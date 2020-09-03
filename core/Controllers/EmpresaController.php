<?php
    use Models\EmpresaModel;
    use Entities\Usuario;
	use Entities\Rol;
    use Entities\Empresa;
    use Models\Conexion;
    
    $method = $_SERVER['REQUEST_METHOD'];
    
    require_once("../Models/EmpresaModel.php");
    require_once("../Entities/Empresa.php");
    require_once("../Models/Conexion.php");
    require_once("../Entities/Usuario.php");
    
    switch ($method) {
        case 'GET'://///////////////////////////////////////////////GET
            switch ($_REQUEST['opcion']) {
                case 1:
                    $acceso;
                    $acceso = verificarAcceso();
                	if($acceso){
                	    $ac = (int) $_REQUEST['activos'];
                        if( $ac== 1)
                            cargarEmpresa(1);
                        else if($ac == 0)
                            cargarEmpresa(0);
                	}else {
                	    $arreglo["data"][] = array();
                        echo json_encode($m, JSON_FORCE_OBJECT);
                	}
                    break;
                case 2:
                    $acceso;
                    $acceso = verificarAcceso();
                	if($acceso){
                	    imprimirLista();
                	}else {
                	    header("location:/");
                	}
                    break;
                case 3:
                    $acceso;
                    $acceso = verificarAcceso();
                	if($acceso){
                	    imprimirficha($_REQUEST['id']);
                	}else {
                	    header("location:/");
                	}
                    break;
                default:
                    print('EmpresaController no hay opcion');
                    break;
            }
            break;
        case 'POST'://///////////////////////////////////////////////POST
            $post_vars = $_POST;
            $m['mensaje'] = guardarDatos($post_vars);
            echo json_encode($m, JSON_FORCE_OBJECT);    
            break;
        case 'PUT':///////////////////////////////////////////////////PUT
            print("PUT\n");
            parse_str(file_get_contents('php://input'), $put_vars);  
            var_dump($put_vars);
            break;
        case 'DELETE':///////////////////////////////////////////////DELETE
            print("DELETE\n");
            parse_str(file_get_contents('php://input'), $delete_vars);  
            var_dump($delete_vars);
            break;
    }
    exit();
/////////////////////////////////////////////////////////cargarEmpresas(activos), enviar la lista//////////////////////////////////////////////////////
    function cargarEmpresa($activo){
        $empresaModel = new EmpresaModel();
        $sql = "select * from cia.empresa e INNER JOIN cia.participante p ON (e.empresaID = p.participanteID) where p.activo = ".$activo.";";
        $resultados = $empresaModel->listarEmpresas($sql);
        if($resultados != null && !empty($resultados)){
            $empresaModel->con = null;
            Conexion::setConnection(null);
            foreach ($resultados as $value) {
                $arreglo["data"][] = $value;
            }
            echo json_encode($arreglo);
        }else
            print("ERROR al cargar las empresas o no hay registros");
    }
////////////////////////////////////////////verificar acceso///////////////////////////////////////////////////////////////////////
    function verificarAcceso(){
        session_set_cookie_params(0);
        session_cache_limiter('nocache');
    	$cache_limiter = session_cache_limiter();
    	session_cache_expire(0);
    	$cache_expire = session_cache_expire();
    	session_start();
    	$a = true;
    	if (!isset($_SESSION['usuario'])) {
    		$a = false;
    	}else{
    		$usuario = unserialize($_SESSION['usuario']);
    		if (!$usuario instanceof Usuario) {
    		    $a = false;
    		}else{
    			$id = (int) $usuario->m_Rol->getRolID();
    			if($usuario->m_Rol->getnombreRol() != "admin" && $id != 1)
    				$a = false;
    		}
    	}
    	return $a;
    }
////////////////////////////////////////funcion guardarDatos($post_Vars), retorno CORRECTO, INCORRECTO, ERROR ///////////////////////////////
    function guardarDatos($post_vars){
        $empresaModel = new EmpresaModel();
        $empresa = new Empresa(trim($post_vars['nombreCompleto']), null, trim($post_vars['correo']), trim($post_vars['telefono']), null, null);
        $empresa->setnombreEmpresa(trim($post_vars["nombreEmpresa"]));
        $empresa->m_CuidadDep = new Entities\CuidadDep();
        $empresa->m_CuidadDep->setCuidadDepID(trim($post_vars["cuidadDep"]));
        $empresa->setdireccion(trim($post_vars["direccion"]));
        $empresa->setnumeroEmpleados(trim($post_vars["numeroEmpleados"]));
        $empresa->setcargo(trim($post_vars["cargo"]));
        $empresa->setanioConstitucion(trim($post_vars["anioConstitucion"]));
        $empresa->m_SectorEconomico =  new Entities\SectorEconomico();
        $empresa->m_SectorEconomico->setSectorEconomicoID(trim($post_vars["sectorEconomico"]));
        $empresa->setdescripcionEmpresa(trim($post_vars["descripcionEmpresa"]));
        $empresa->setdescripcionProblema(trim($post_vars["descripcionProblema"]));
        $empresa->setsolucionTemporal(trim($post_vars["solucionTemporal"]));
        if(isset($post_vars["planCrecimiento"]) && $post_vars["planCrecimiento"] != ""){
            $empresa->setplanCrecimiento(trim($post_vars["planCrecimiento"]));
        }else{            
            $empresa->setplanCrecimiento(null);
        }
        $empresa->setrazonSeleccion(trim($post_vars["razonSeleccion"]));
        
        $valid = $empresa->validarDatos();
        if($valid){
            
            $listaEm = $empresaModel->listarRegistros(1);
            foreach ($listaEm as $registro) {
                if(in_array($empresa->getcorreo(), $registro)){
                    return "INCORRECTO";
                    break;
                }
                if(in_array($empresa->gettelefono(), $registro)){
                    return "INCORRECTO";
                    break;
                }
            }
            $r = (int) $empresaModel->guardarRegistro($empresa);
            if($r > 0){
                $empresaModel->con = null;
                Conexion::setConnection(null);
                return "CORRECTO";
            }else{
                $empresaModel->con = null;
                Conexion::setConnection(null);
                return 'INCORRECTO';
            }
        }else
            return 'INCORRECTO';
    }
//////////////////////////////7//////////////////////////////////////imprimir lista de empresas/////////////////////////////////////////////////////////
    function imprimirLista(){
        $empresaModel = new EmpresaModel();
        $sql = "select * from cia.empresa e INNER JOIN cia.participante p ON (e.empresaID = p.participanteID) 
            INNER JOIN cia.cuidaddep c ON (e.cuidadDepID = c.cuidadDepID) 
            INNER JOIN cia.sectoreconomico s ON (e.sectorEconomicoID = s.sectorEconomicoID) where p.activo = 1;";
        $resultados = $empresaModel->listarEmpresas($sql);
        $empresaModel->con = null;
        Conexion::setConnection(null);
        $now = date("d-m-Y");
        require_once("../Libs/mpdf/mpdf.php");
        $body='<body>
        	<header class="clearfix">
        		<div class="container">
        		    <figure>
        				<img style="border-radius: 5px;" class="logo" src="../../Views/assets/ico/apple-touch-icon-72-precomposed.jpg" alt="">
        			</figure>
        			<div class="company-info" style="text-align: right;">
        				<h2 class="title">Centro de Innovación</h2>
        				<div class="address">
        					<p>Uinversidad Centroamericana</p>
        				</div>
        				<div class="phone">
        					<p>
        						Lista de Participantes
        					</p>
        				</div>
        			</div>
        		</div>
        	</header>
        	<section>
        		<div class="container">
        			<div class="details clearfix">
        				<div class="client left">
        					<p class="name">Lista de empresas</p>
        					<p>TOTAL: </p>
        					<p style="margin-left: 4em">'.count($resultados).' empresas</p>
        				</div>
        				<div class="data right">
        					<div class="title">Centro de Innovación</div>
        					<div class="date">
        						Fecha de creación: <i id="fechaC">'.$now.'</i>
        					</div>
        				</div>
        			</div>
        			<table id="tabla_d" border="0" cellspacing="0" cellpadding="0">
        				<thead>
        					<tr>
        						<th class="desc">EMPRESAS</th>
        					</tr>
        				</thead>
        				<tbody>';
        				$i = "nada";
    					foreach ($resultados as $value) {
    					    $body.='<tr style="border-bottom: 1px solid black;">
                						<td class="desc" style="padding: 10px 10px;">
                							<h3 style="color:#3c7739; font-weight:600; font-size:1.4rem;">Nombre Completo:</h3>
                							<p style="font-weight:normal; font-size:1em; text-align:justify;">'.$value["nombreCompleto"].'</p>
                							
                							<h3 style="color:#57a03a; font-weight:600; font-size:1.4rem;">Correo:</h3>
                							<p style="font-weight:normal; font-size:1em;">'.$value["correo"].'</p>
                							
                							<h3 style="color:#57a03a; font-weight:600; font-size:1.4rem;">Teléfono:</h3>
                							<p style="font-weight:normal; font-size:1em;">'.$value["telefono"].'</p>
                							
                							<h3 style="color:#57a03a; font-weight:600; font-size:1.4rem;">Nombre de la Empresa:</h3>
                							<p style="font-weight:normal; font-size:1em;">'.$value["nombreEmpresa"].'</p>
                						    
                							<h3 style="color:#57a03a; font-weight:600; font-size:1.4rem;">Cuidad o Departamento:</h3>
                							<p style="font-weight:normal; font-size:1em;">'.$value["nombreCuidad"].'</p>
                							
                							<h3 style="color:#57a03a; font-weight:600; font-size:1.4rem;">Dirección:</h3>
                							<p style="font-weight:normal; font-size:1em;">'.$value["direccion"].'</p>
                							
                							<h3 style="color:#57a03a; font-weight:600; font-size:1.4rem;">Número de empleados:</h3>
                							<p style="font-weight:normal; font-size:1em;">'.$value["numeroEmpleados"].'</p>
                							
                							<h3 style="color:#57a03a; font-weight:600; font-size:1.4rem;">Cargo:</h3>
                							<p style="font-weight:normal; font-size:1em;">'.$value["cargo"].'</p>
                							
                							<h3 style="color:#57a03a; font-weight:600; font-size:1.4rem;">Año de constitución:</h3>
                							<p style="font-weight:normal; font-size:1em;">'.$value["anioConstitucion"].'</p>
                							
                							<h3 style="color:#57a03a; font-weight:600; font-size:1.4rem;">Sector económico:</h3>
                							<p style="font-weight:normal; font-size:1em;">'.$value["nombreSectorEc"].'</p>
                							
                							<h3 style="color:#57a03a; font-weight:600; font-size:1.4rem;">Descripción de la empresa (negocio):</h3>
                							<p style="font-weight:normal; font-size:1em;">'.$value["descripcionEmpresa"].'</p>
                							
                							<h3 style="color:#57a03a; font-weight:600; font-size:1.4rem;">Descripción del problema:</h3>
                							<p style="font-weight:normal; font-size:1em;">'.$value["descripcionProblema"].'</p>
                							
                							<h3 style="color:#57a03a; font-weight:600; font-size:1.4rem;">Solución temporal, beneficios esperados:</h3>
                							<p style="font-weight:normal; font-size:1em;">'.$value["solucionTemporal"].'</p>
                							
                							<h3 style="color:#57a03a; font-weight:600; font-size:1.4rem;">Plan de crecimiento de la empresa:</h3>
                							<p style="font-weight:normal; font-size:1em;">'.$v = ($value["planCrecimiento"] !="")? $value["planCrecimiento"] : "N/A".'</p>
                							
                							<h3 style="color:#57a03a; font-weight:600; font-size:1.4rem;">Razón para ser seleccionado:</h3>
                							<p style="font-weight:normal; font-size:1em;">'.$value["razonSeleccion"].'</p>
                							<br/>
                						</td>
                					</tr>';
    					}
            $body .= '</tbody>
        			</table>
        		</div>
        	</section>
        	<footer>
        		<div class="container">
        			<div class="notice">
        				<strong style="font-weight: bold;">Lista de Empresas</strong>
        				<div>Managua, Nicaragua</div>
        			</div>
        			<div class="end">Centro de Innovación Abierta - UCA</div>
        		</div>
        	</footer>
        </body>';
        $css = file_get_contents("../../Views/assets/css/lista.css");
        $mpdf = new mPDF('c','A4');
        $mpdf->SetFooter('CIA-UCA||{PAGENO}');
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->writeHTML($css, 1);
        $mpdf->writeHTML($body);
        $mpdf->Output('Lista_empresas.pdf', 'I');
        exit();
    }
//////////////////////////////7//////////////////////////////////////imprimir ficha de empresa/////////////////////////////////////////////////////////
    function imprimirficha($id){
        if(isset($id) && !empty($id) && is_numeric($id))
            $id = (int) $id;
        else
            die();
        $empresaModel = new EmpresaModel();
        $sql = "select * from cia.empresa e INNER JOIN cia.participante p ON (e.empresaID = p.participanteID) 
            INNER JOIN cia.cuidaddep c ON (e.cuidadDepID = c.cuidadDepID) 
            INNER JOIN cia.sectoreconomico s ON (e.sectorEconomicoID = s.sectorEconomicoID) where p.activo = 1 and e.empresaID = $id;";
        $resultado = $empresaModel->listarEmpresas($sql);
        $empresaModel->con = null;
        Conexion::setConnection(null);
        $now = date("d-m-Y");
        require_once("../Libs/mpdf/mpdf.php");
        $body='<body>
	<section>
		<div class="container">
			<div class="details clearfix" style="margin-top:30px; background-color:white; color:black;">
				<div class="client" style="width:100%; text-align:center;">
					<h1 style="font-size:21px; font-weight:bold; margin-top:30px;">Solicitud de participación para la Temporada de Innovación Abierta</h1>
				</div>
			</div>
			<p style="font-size:14px; margin:20px; text-align:justify; font-family:Arial;"><b>El Centro de Innovación Abierta</b> busca identificar, priorizar y atender las necesidades de medios de comunicación, empresas privadas, organizaciones, instituciones, cámaras de comercio, pymes  etc, con el fin de impulsar la innovación, la creación de redes, intercambio de conocimientos e integración de los actores para fomentar una cultura de innovación universitaria y vincular la realidad empresarial en un espacio de Co-creación manteniéndonos fieles a la necesidad de cuidar a las personas, instituciones y medio ambiente.</p>
			<div class="clearfix box-blue">
				<h2><b>Los requisitos para participar de la I Temporada de Innovación:</b></h2>
				<ul>
					<li>Empresas de  cualquier sector económico.</li>
					<li>Ubicadas a nivel nacional en la zona urbana-rural.</li>
					<li>Empresa formalizada o en proceso legalización.</li>
					<li>Al menos 4 años en el mercado.</li>
					<li>Con deseos de implementar nuevas metodologías de trabajo.</li>
					<li>Interesadas en participar y aprender en esquemas de innovación abierta.</li>
				</ul>
			</div>
			<div class="clearfix box-blue" style="margin-bottom:230px;">
				<h2><b>Las empresas participantes buscan:</b></h2>
				<ul>
					<li>Asesoría técnica e implementación de software.</li>
					<li>Consejería legal para formalizar la empresa (incluyendo propiedad intelectual, del producto y de la marca).</li>
					<li>Búsqueda de nuevos clientes.</li>
					<li>Asesoría en manejo contable y financiero.</li>
					<li>Fortalecimiento en temas relacionados con manejo empresarial (habilidades gerenciales).</li>
					<li>Fortalecer su estructura operacional.</li>
					<li>Fortalecer sus habilidades de gestión empresarial.</li>
					<li>Posicionar sus productos y marcas a nivel local, nacional e internacional.</li>
					<li>Facilitar el acceso a recursos financieros para el crecimiento del negocio.</li>
				</ul>
			</div>
			<hr>
			<table class="demo" style="border:1px solid #C0C0C0; border-collapse:collapse; padding:5px; margin-bottom:0px;">
				<thead style="display: table-header-group; vertical-align: middle; border-color: inherit;">
					<tr>
						<th style="border:1px solid #C0C0C0; padding:5px; background:#9ebdec; color: black; font-size: 16px;" colspan="2">Datos Generales de la Empresa</th>
					</tr>
				</thead>
				<tbody>
					<tr style="border:1px solid #C0C0C0;">
						<td style="border:1px solid #C0C0C0; padding:5px; font-family:Arial; text-align:justify; width:50%;">
							<h3 style="font-weight:600; font-size:1.2rem;"><b>Nombre de la Empresa:</b></h3>
                			<p style="font-weight:normal; font-size:13px;">'.$resultado[0]["nombreEmpresa"].'</p>
						</td>
						<td style="border:1px solid #C0C0C0; padding:5px; font-family:Arial; text-align:justify; width:50%;">
							<h3 style="font-weight:600; font-size:1.2rem;"><b>Sector Económino:</b></h3>
                			<p style="font-weight:normal; font-size:13px;">'.$resultado[0]["nombreSectorEc"].'</p>
						</td>
					</tr>
					<tr style="border:1px solid #C0C0C0;">
						<td style="border:1px solid #C0C0C0; padding:5px; font-family:Arial; text-align:justify;" colspan="2">
							<h3 style="font-weight:600; font-size:1.2rem;"><b>Dirección:</b></h3>
                			<p style="font-weight:normal; font-size:13px;">'.$resultado[0]["direccion"].'</p>
						</td>
					</tr>
					<tr style="border:1px solid #C0C0C0;">
						<td style="border:1px solid #C0C0C0; padding:5px; font-family:Arial; text-align:justify; width:50%;">
							<h3 style="font-weight:600; font-size:1.2rem;"><b>Responsable:</b></h3>
                			<p style="font-weight:normal; font-size:13px;">'.$resultado[0]["nombreCompleto"].'</p>
						</td>
						<td style="border:1px solid #C0C0C0; padding:5px; font-family:Arial; text-align:justify; width:50%;">
							<h3 style="font-weight:600; font-size:1.2rem;"><b>Cargo:</b></h3>
                			<p style="font-weight:normal; font-size:13px;">'.$resultado[0]["cargo"].'</p>
						</td>
					</tr>
					<tr style="border:1px solid #C0C0C0;">
						<td style="border:1px solid #C0C0C0; padding:5px; font-family:Arial; text-align:justify; width:50%;">
							<h3 style="font-weight:600; font-size:1.2rem;"><b>N° de empleados:</b></h3>
                			<p style="font-weight:normal; font-size:13px;">'.$resultado[0]["numeroEmpleados"].'</p>
						</td>
						<td style="border:1px solid #C0C0C0; padding:5px; font-family:Arial; text-align:justify; width:50%;">
							<h3 style="font-weight:600; font-size:1.2rem;"><b>Año de constitución:</b></h3>
                			<p style="font-weight:normal; font-size:13px;">'.$resultado[0]["anioConstitucion"].'</p>
						</td>
					</tr>
					<tr style="border:1px solid #C0C0C0;">
						<td style="border:1px solid #C0C0C0; padding:5px; font-family:Arial; text-align:justify; width:50%;">
							<h3 style="font-weight:600; font-size:1.2rem;"><b>Télefono:</b></h3>
                			<p style="font-weight:normal; font-size:13px;">'.$resultado[0]["telefono"].'</p>
						</td>
						<td style="border:1px solid #C0C0C0; padding:5px; font-family:Arial; text-align:justify; width:50%;">
							<h3 style="font-weight:600; font-size:1.2rem;"><b>E-mail:</b></h3>
                			<p style="font-weight:normal; font-size:13px;">'.$resultado[0]["correo"].'</p>
						</td>
					</tr>
					<tr style="border:1px solid #C0C0C0;">
						<td style="border:1px solid #C0C0C0; padding:5px; font-family:Arial; text-align:justify; width:50%;">
							<h3 style="font-weight:600; font-size:1.2rem;"><b>Cuidad, Departamento:</b></h3>
                			<p style="font-weight:normal; font-size:13px;">'.$resultado[0]["nombreCuidad"].'</p>
						</td>
						<td style="border:1px solid #C0C0C0; padding:5px; font-family:Arial; text-align:justify; width:50%;">
							<h3 style="font-weight:600; font-size:1.2rem;"><b>País:</b></h3>
                			<p style="font-weight:normal; font-size:13px;">Nicaragua</p>
						</td>
					</tr>
				</tbody>
			</table>
			<table class="demo" style="border:1px solid #C0C0C0; border-collapse:collapse; padding:5px; margin-bottom:0px;">
				<thead style="display: table-header-group; vertical-align: middle; border-color: inherit;">
					<tr>
						<th style="border:1px solid #C0C0C0; padding:5px; background:#9ebdec; color: black; font-size: 16px;" colspan="2">Información Básica / Identificación de la problemática</th>
					</tr>
				</thead>
				<tbody>
					<tr style="border:1px solid #C0C0C0;">
						<td style="border:1px solid #C0C0C0; padding:5px; font-family:Arial; text-align:justify; width:30%;">
							<h3 style="font-weight:600; font-size:1.2rem;"><b>Breve descripción del negocio, los productos o servicios que ofrece (¿A qué se dedica?, ¿Quiénes son sus beneficiarios, clientes/ usuarios?</b></h3>
						</td>
						<td style="border:1px solid #C0C0C0; padding:5px; font-family:Arial; text-align:justify; width:70%;">
                			<p style="font-weight:normal; font-size:13px;">'.$resultado[0]["descripcionEmpresa"].'</p>
						</td>
					</tr>
					<tr style="border:1px solid #C0C0C0;">
						<td style="border:1px solid #C0C0C0; padding:5px; font-family:Arial; text-align:justify; width:30%;">
							<h3 style="font-weight:600; font-size:1.2rem;"><b>Descripción del problema - ¿Cuál es la problemática de su empresa? ¿Quién lo tiene? (especificar el o las áreas afectadas) ¿Quiénes participan? (actores involucrados)</b></h3>
						</td>
						<td style="border:1px solid #C0C0C0; padding:5px; font-family:Arial; text-align:justify; width:70%;">
                			<p style="font-weight:normal; font-size:13px;">'.$resultado[0]["descripcionProblema"].'</p>
						</td>
					</tr>
					<tr style="border:1px solid #C0C0C0;">
						<td style="border:1px solid #C0C0C0; padding:5px; font-family:Arial; text-align:justify; width:30%; height:100px;">
							<h3 style="font-weight:600; font-size:1.2rem;"><b>¿Hay alguna solución actual? ¿Qué beneficios se esperan?</b></h3>
						</td>
						<td style="border:1px solid #C0C0C0; padding:5px; font-family:Arial; text-align:justify; width:70%;">
                			<p style="font-weight:normal; font-size:13px;">'.$resultado[0]["solucionTemporal"].'</p>
						</td>
					</tr>
					<tr style="border:1px solid #C0C0C0;">
						<td style="border:1px solid #C0C0C0; padding:5px; font-family:Arial; text-align:justify; width:30%;">
							<h3 style="font-weight:600; font-size:1.2rem;"><b>¿Qué le gustaría lograr con su empresa u organización? Cuéntenos sus planes de crecimiento o expansión para su negocio.</b></h3>
						</td>
						<td style="border:1px solid #C0C0C0; padding:5px; font-family:Arial; text-align:justify; width:70%;">
                			<p style="font-weight:normal; font-size:13px;">'.$v = ($resultado[0]["planCrecimiento"] !="")? $resultado[0]["planCrecimiento"] : "N/A".'</p>
						</td>
					</tr>
					<tr> style="border:1px solid #C0C0C0;">
						<td style="border:1px solid #C0C0C0; padding:5px; font-family:Arial; text-align:justify; width:30%; height:100px;">
							<h3 style="font-weight:600; font-size:1.2rem;"><b>Cuéntenos ¿Por qué debe ser seleccionada su empresa para participar en la I Temporada de Innovación?</b></h3>
						</td>
						<td style="border:1px solid #C0C0C0; padding:5px; font-family:Arial; text-align:justify; width:70%;">
                			<p style="font-weight:normal; font-size:13px;">'.$resultado[0]["razonSeleccion"].'</p>
						</td>
					</tr>
					<tr style="border:1px solid #C0C0C0;">
						<td style="border:1px solid #C0C0C0; padding:5px; padding-bottom:160px; font-family:Arial; text-align:center;" colspan="2">
							<h3 style="font-weight:600; font-size:1.2rem;"><b>Resultados que espera de esta nueva metodología de trabajo</b></h3>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</section>
	<footer>
		<div class="container">
			<br>
			<p style="font-size:14px; margin:20px; text-align:justify; font-family:Arial;"><b>Por medio de la presente, yo certifico que las declaraciones hechas en respuesta a las preguntas anteriores son verdaderas y completas de acuerdo a la realidad de la empresa y estas serán analizadas para participar en la I Temporada de Innovación Abierta de la UCA.</b></p>
			
		</div>
	</footer>
	<section>
		<div class="details clearfix" style="background-color:#fff; margin-top: 90px;">
			<div class="client left" style="width:30%; margin-left:10%;">
				<p class="name" style="font-size:1.3em; color:black; text-align:center; border-top: 2px solid black;">Nombre y Firma</p>
			</div>
			<div class="data right" style="width:30%; margin-right:10%;">
				<div class="title" style="color:black; text-align:center; border-top: 2px solid black; text-transform:initial;">Fecha de registro</div>
			</div>
		</div>
	</section>
    </body>';
        $css = file_get_contents("../../Views/assets/css/lista.css");
        $mpdf = new mPDF('c','A4');
        $mpdf->defaultheaderfontsize=14;
        $mpdf->defaultheaderfontstyle='B';
        $mpdf->defaultheaderline=4;
        $mpdf->SetHeader('|CENTRO DE INNOVACIÓN ABIERTA - Ficha de descripción de Pymes|');
        $mpdf->SetHTMLFooter('<div style="text-align:center; border-top:1px solid black; margin-top:3px;">Responsable: MSc. Evelyng Martínez  Correo: <a href="">ores@uca.edu.ni</a> -   / Teléfono: 2278-3923 ext. 1359</div>');
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->writeHTML($css, 1);
        $mpdf->writeHTML($body);
        $mpdf->Output('ficha de participación.pdf', 'I');
        exit();
    }
?>