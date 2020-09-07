<?php
    use Models\SolicitanteModel;
    use Entities\Usuario;
    use Entities\Rol;
    use Entities\Solicitante;
    use Models\Conexion;
    
    $method = $_SERVER['REQUEST_METHOD'];
    
    require_once("../Models/SolicitanteModel.php");
    require_once("../Entities/Solicitante.php");
    require_once("../Models/Conexion.php");
    require_once("../Entities/Usuario.php");
    require_once("../Entities/CiudadDep.php");
    require_once("../Entities/SectorEconomico.php");
    
    switch ($method) {
        case 'GET'://///////////////////////////////////////////////GET
            switch ($_REQUEST['opcion']) {
                case 1:
                    $acceso;
                    $acceso = verificarAcceso();
                	if($acceso){
                	    $ac = (int)addslashes($_REQUEST['activos']);
                        if( $ac== 1)
                            cargarSolicitantes(1);
                        else if($ac == 0)
                            cargarSolicitantes(0);
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
                    print('SolicitanteController no hay opcion');
                    break;
            }
            break;
        case 'POST'://///////////////////////////////////////////////POST
            
            $post_vars = $_POST;
            if(sizeof($post_vars) <= 0)
                $_POST = json_decode(file_get_contents('php://input'), true);
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
/////////////////////////////////////////////////////////cargarSolicitantes(activos), enviar la lista//////////////////////////////////////////////////////
    function cargarSolicitantes($activo){
        $solicitanteModel = new SolicitanteModel();
        $sql = "select * from solicitante s where s.activo = ".$activo.";";
        $resultados = $solicitanteModel->listarSolicitantes($sql);
        if($resultados != null && !empty($resultados)){
            $solicitanteModel->con = null;
            Conexion::setConnection(null);
            foreach ($resultados as $value) {
                $arreglo["data"][] = $value;
            }
            echo json_encode($arreglo);
        }else
            print("ERROR al cargar los solicitantes o no hay registros");
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
function guardarDatos($post_vars) {
    $solicitanteModel = new SolicitanteModel();
    $solicitante = new Solicitante();
    $solicitante->setNombreCompleto(trim($post_vars['nombreCompleto']));
    $solicitante->setCorreo(trim($post_vars['correo']));
    $solicitante->setTelefono(trim($post_vars['telefono']));
    $solicitante->setDireccion(trim($post_vars['direccion']));
    $solicitante->m_CiudadDep = new Entities\CiudadDep();
    $solicitante->m_CiudadDep->setCiudadDepID (trim($post_vars["cuidadDep"]));
    $solicitante->m_SectorEconomico = new Entities\SectorEconomico();
    $solicitante->m_SectorEconomico->setSectorEconomicoID(trim($post_vars["sectorEconomico"]));
//        $solicitante->setIdentificacion(trim($post_vars['identificacion']));
    $now = date("Y-m-d");
    $solicitante->setFechaRegistro($now);
    $solicitante->setActivo(1);

    $valid = $solicitante->validarDatos();
    if ($valid) {
        $sql = "select * from solicitante s where s.activo =1;";
        $listaD = $solicitanteModel->listarSolicitantes($sql);
        foreach ($listaD as $registro) {
            if (in_array($solicitante->getCorreo(), $registro)) {
                Conexion::setConnection(null);
                return "INCORRECTO";
                break;
            }
            if (in_array($solicitante->getTelefono(), $registro)) {
                Conexion::setConnection(null);
                return "INCORRECTO";
                break;
            }
        }
        $r = (int) $solicitanteModel->guardarRegistro($solicitante);
        if ($r > 0) {
            $solicitanteModel->con = null;
            Conexion::setConnection(null);
            $to_email = 'alexanderperalta54@gmail.com';
            $subject = 'Testing PHP Mail';
            $message = "<h1>Un solicitante ha realizado una consulta</h1> <h2>Datos del solicitante: </h2><hr style='height:2px;border-width:0;color:gray;background-color:gray'>".
                    "<p><b>Nombre:</b>  ".$solicitante->getNombreCompleto()."</p>".
                    "<p><b>Teléfono:</b>  ".$solicitante->getTelefono()."</p>".
                    "<p><b>Correo:</b>  ".$solicitante->getCorreo()."</p>";
            
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: au706518@gmail.com';
            if (mail($to_email, $subject, $message, $headers)) {
                return "CORRECTO";
            } else {
                return "CORRECTO";
            }
        } else {
            $solicitanteModel->con = null;
            Conexion::setConnection(null);
            return 'INCORRECTO';
        }
    } else
        return 'INCORRECTO';
}

//////////////////////////////7//////////////////////////////////////imprimir lista de solicitantes/////////////////////////////////////////////////////////
    function imprimirLista(){
        $solicitanteModel = new SolicitanteModel();
        $sql = "select * from cia.solicitante s where s.activo = 1;";
        $resultados = $solicitanteModel->listarSolicitantes($sql);
        $solicitanteModel->con = null;
        Conexion::setConnection(null);
        $now = date("d-m-Y");
        
        require_once("../Libs/mpdf/vendor/autoload.php");
        $body='<body>
        	<header class="clearfix">
        		<div class="container">
        		    <figure>
        				<img style="border-radius: 5px;" class="logo" src="../../Views/assets/ico/apple-touch-icon.png" alt="">
        			</figure>
        			<div class="company-info" style="text-align: right;">
        				<h2 class="title">Centro de Innovación</h2>
        				<div class="address">
        					<p>Uinversidad Centroamericana</p>
        				</div>
        				<div class="phone">
        					<p>
        						Lista de solicitantes
        					</p>
        				</div>
        			</div>
        		</div>
        	</header>
        	<section>
        		<div class="container">
        			<div class="details clearfix">
        				<div class="client left">
        					<p class="name">Lista de solicitantes</p>
        					<p>TOTAL: </p>
        					<p style="margin-left: 4em">'.count($resultados).' solicitantes</p>
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
        						<th class="desc">SOLICITANTES</th>
        					</tr>
        				</thead>
        				<tbody>';
        				$i = "nada";
    					foreach ($resultados as $value) {
    					    $body.='<tr style="border-bottom: 1px solid black;">
                						<td class="desc" style="padding: 10px 10px;">
                							<h3 class="htr" style="color:#3c7739; font-weight:600; font-size:1.4rem;">Nombre Completo:</h3>
                							<p style="font-weight:normal; font-size:1em; text-align:justify;">'.$value["nombreCompleto"].'</p>
                							
                							<h3 style="color:#57a03a; font-weight:600; font-size:1.4rem;">Correo:</h3>
                							<p style="font-weight:normal; font-size:1em;">'.$value["correo"].'</p>
                							
                							<h3 style="color:#57a03a; font-weight:600; font-size:1.4rem;">Teléfono:</h3>
                							<p style="font-weight:normal; font-size:1em;">'.$value["telefono"].'</p>
                							
                							<h3 style="color:#57a03a; font-weight:600; font-size:1.4rem;">Dirección:</h3>
                							<p style="font-weight:normal; font-size:1em;">'.$value["direccion"].'</p>
                							
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
                                            <strong style="font-weight: bold;">Lista de Solicitantes</strong>
        				<div>Managua, Nicaragua</div>
        			</div>
        			<div class="end">Centro de Innovación Abierta - UCA</div>
        		</div>
        	</footer>
        </body>';
        $css = file_get_contents("../../Views/assets/css/lista.css");
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
        $mpdf->SetFooter('CIA-UCA||{PAGENO}');
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->writeHTML($css, 1);
        $mpdf->writeHTML($body);
        $mpdf->Output('Lista_solicitantes.pdf', 'I');
        exit();
    }
//////////////////////////////7//////////////////////////////////////imprimir ficha de solicitante/////////////////////////////////////////////////////////
    function imprimirficha($id){
        if(isset($id) && !empty($id) && is_numeric($id))
            $id = (int) $id;
        else
            die();
        $solicitanteModel = new SolicitanteModel();
        $sql = "select * from cia.solicitante s where s.activo = 1 and s.solicitanteId = $id;";
        $resultado = $solicitanteModel->listarSolicitantes($sql);
        $solicitanteModel->con = null;
        Conexion::setConnection(null);
        $now = date("d-m-Y");
        require_once("../Libs/mpdf/vendor/autoload.php");
        $body='<body>
	<section>
		<div class="container">
			<div class="details clearfix" style="margin-top:30px; background-color:white; color:black;">
				<div class="client" style="width:100%; text-align:center;">
					<h1 style="font-size:21px; font-weight:bold; margin-top:30px;">Ficha de paticipación para Solicitante </h1>
				</div>
			</div>
			<p style="font-size:14px; margin:20px; text-align:justify; font-family:Arial;">Hazte Socio. Únete a Centro de Innovación Abierta UCA, para contribuir al  crecimiento del ecosistema de innovación, apoyar la competitividad de la región y co-crear valor para la sociedad a través de la innovación abierta. Como socio del Centro, recibirás nuestro blog con información acercar de las actividades, jornadas y temporadas de Innovación, los proyectos en los que están participando nuestros estudiantes y las soluciones e emprendimientos creativos incubados una vez finalizadas las temporadas. Además la oportunidad de la que tu marca forme parte de nuestros aliados en la plataforma web del Centro de Innovación Abierta UCA.</p>
			<hr>
			<table class="demo" style="border:1px solid #C0C0C0; border-collapse:collapse; padding:5px; margin-bottom:0px;">
				<thead style="display: table-header-group; vertical-align: middle; border-color: inherit;">
					<tr>
						<th style="border:1px solid #C0C0C0; padding:5px; background:#9ebdec; color: black; font-size: 16px;" colspan="2">Datos Generales</th>
					</tr>
				</thead>
				<tbody>
					<tr style="border:1px solid #C0C0C0;">
						<td style="border:1px solid #C0C0C0; padding:5px; font-family:Arial; text-align:justify;" colspan="2">
							<h3 style="font-weight:600; font-size:1.2rem;"><b>Nombre Completo:</b></h3>
                			<p style="font-weight:normal; font-size:13px;">'.$resultado[0]["nombreCompleto"].'</p>
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
						<td style="border:1px solid #C0C0C0; padding:5px; font-family:Arial; text-align:justify; width:50%;">
							<h3 style="font-weight:600; font-size:1.2rem;"><b>Dirección:</b></h3>
                			<p style="font-weight:normal; font-size:13px;">'.$resultado[0]["direccion"].'</p>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</section>
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
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
        $mpdf->defaultheaderfontsize=14;
        $mpdf->defaultheaderfontstyle='B';
        $mpdf->defaultheaderline=4;
        $mpdf->SetHeader('|Buro - Ficha de inscripción del solicitante|');
        $mpdf->SetHTMLFooter('<div style="text-align:center; border-top:1px solid black; margin-top:3px;">Responsable: MSc. Evelyng Martínez  Correo: <a href="">ores@uca.edu.ni</a> -   / Teléfono: 2278-3923 ext. 1359</div>');
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->writeHTML($css, 1);
        $mpdf->writeHTML($body);
        $mpdf->Output('ficha de solicitante.pdf', 'I');
        exit();
    }
?>