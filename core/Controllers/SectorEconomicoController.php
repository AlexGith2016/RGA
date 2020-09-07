<?php
    use Models\SectorEconomicoModel;
    use Models\SolicitanteModel;
    use Models\Conexion;
	use Entities\Usuario;
	use Entities\Rol;
	use Entities\SectorEconomico;
    
    $method = $_SERVER['REQUEST_METHOD'];
    
    require_once("../Models/SectorEconomicoModel.php");
    require_once("../Models/Conexion.php");
    require_once("../Entities/Usuario.php");
    
    switch ($method) {
        case 'GET':
            switch ($_REQUEST['opcion']) {
                case 1:
                    cargarSectores();
                    break;
                default:
                    print('SectorEconomicoController no hay opcion');
                    break;
            }
            break;
        case 'POST':
            $post_vars = $_POST;
            $acceso;
            $acceso = verificarAcceso();
        	if($acceso){
        	    $m= guardarDatos($post_vars);
                echo json_encode($m, JSON_FORCE_OBJECT);
        	}else {
        	    $m['mensaje'] = "<p>No tiene acceso a esta acción</p>";
                $m['titulo'] = "SIN ACCESO";
                $m['tipo'] = "danger";
                echo json_encode($m, JSON_FORCE_OBJECT);
        	}
            break;
        case 'PUT':
            parse_str(file_get_contents('php://input'), $put_vars); 
            $acceso;
            $acceso = verificarAcceso();
            if($acceso){
        	    $m= actualzarDatos($put_vars);
                echo json_encode($m, JSON_FORCE_OBJECT);
        	}else {
        	    $m['mensaje'] = "<p>No tiene acceso a esta acción</p>";
                $m['titulo'] = "SIN ACCESO";
                $m['tipo'] = "danger";
                echo json_encode($m, JSON_FORCE_OBJECT);
        	}
            break;
        case 'DELETE':
            parse_str(file_get_contents('php://input'), $delete_vars);  
            $acceso;
            $acceso = verificarAcceso();
            if($acceso){
        	    $m= eliminar($delete_vars);
                echo json_encode($m, JSON_FORCE_OBJECT);
        	}else {
        	    $m['mensaje'] = "<p>No tiene acceso a esta acción</p>";
                $m['titulo'] = "SIN ACCESO";
                $m['tipo'] = "danger";
                echo json_encode($m, JSON_FORCE_OBJECT);
        	}
            break;
    }
    exit();
//////////////////////////////////////////////funcion que devuelve lista de sectores economicos///////////////////////////////////////
    function cargarSectores(){
        $seModel = new SectorEconomicoModel();
        $resultados = $seModel->listarSectoresEconomicos();
        if(isset($resultados)){
            $seModel->con = null;
            Conexion::setConnection(null);
            foreach ($resultados as $value) {
                $arreglo["data"][] = $value;
            }
            echo json_encode($arreglo, JSON_UNESCAPED_UNICODE);
        }else
            print("error al cargar los sectores económicos o no hay registros");
        exit();
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
        $seModel = new SectorEconomicoModel();
        $se = new SectorEconomico();
        $se->setnombreSectorEc(trim($post_vars["nombreSectorEc"]));
        $valid = $se->validarDatos(false);
        if($valid){
            $listaSE = $seModel->listarSectoresEconomicos();
            foreach ($listaSE as $registro) {
                if(in_array($se->getnombreSectorEc(), $registro)){
                    $info['mensaje'] = "<p>No pueden existir sectores económicos con el mismo nombre</p>";
                    $info['titulo'] = "INCORRECTO";
                    $info['tipo'] = "warning";
                    Conexion::setConnection(null);
                    return $info;
                    break;
                }
            }
            $r = (int) $seModel->guardarRegistro($se);
            if($r > 0){
                $seModel->con = null;
                Conexion::setConnection(null);
                return verResutados("CORRECTO");
            }else{
                $seModel->con = null;
                Conexion::setConnection(null);
                return verResutados("ERROR");
            }
        }else
            return verResutados("INCORRECTO");
    }
    
////////////////////////////////////////funcion actualizarDatos($put_Vars), retorno CORRECTO, INCORRECTO, ERROR ///////////////////////////////
    function actualzarDatos($put_Vars){
        $seModel = new SectorEconomicoModel();
        $se = new SectorEconomico();
        $se->setnombreSectorEc(trim($put_Vars["nombreSectorEc"]));
        $se->setSectorEconomicoID(trim($put_Vars["sectorEconomicoId"]));
        $valid = $se->validarDatos(true);
        if($valid){
            $listaSE = $seModel->listarSectoresEconomicos();
            foreach ($listaSE as $registro) {
                if(in_array($se->getnombreSectorEc(), $registro)){
                    if(!in_array($se->getSectorEconomicoID(), $registro)){
                        $info['mensaje'] = "<p>No pueden existir sectores económicos con el mismo nombre</p>";
                        $info['titulo'] = "INCORRECTO";
                        $info['tipo'] = "warning";
                        Conexion::setConnection(null);
                        return $info;
                        break;
                    }  
                }
            }
            $r = (int) $seModel->actualizarDatos($se);
            if($r > 0){
                $seModel->con = null;
                Conexion::setConnection(null);
                return verResutados("CORRECTO");
            }else{
                $seModel->con = null;
                Conexion::setConnection(null);
                return verResutados("ERROR");
            }
        }else
            return verResutados("INCORRECTO");
    }
    
////////////////////////////////////////funcion eliminar($delete_vars), retorno CORRECTO, INCORRECTO, ERROR ///////////////////////////////
    function eliminar($delete_vars){
        $seModel = new SectorEconomicoModel();
        $se = new SectorEconomico();
        $se->setSectorEconomicoID(trim($delete_vars["sectorEconomicoId"]));
        
        if($se->getsectorEconomicoID() !== null && !empty($se->getsectorEconomicoID()) && is_numeric($se->getsectorEconomicoID()))
            $valid = true;
    	else
    		$valid = false;
        if($valid){
            require_once("../Models/SolicitanteModel.php");
            $se->setSectorEconomicoID(( int ) $se->getsectorEconomicoID());
            $solicitanteModel = new SolicitanteModel();
            $sql = $sql = "select s.sectorEconomicoId from solicitante s where s.sectorEconomicoId = ". $se->getsectorEconomicoID().";";
            $listaEm = $solicitanteModel->listarSolicitantes($sql);
            if(count($listaEm) > 0){
                $info['mensaje'] = "<p>No puede borrar este registro ya que hay solicitantes ascociados a él, cambie el sector de los solicitantes para eliminarlo</p>";
                $info['titulo'] = "INCORRECTO";
                $info['tipo'] = "warning";
                $solicitanteModel->con = null;
                Conexion::setConnection(null);
                return $info;
            }
            $solicitanteModel->con = null;
            $r = (int) $seModel->eliminarDatos($se);
            if($r > 0){
                $seModel->con = null;
                Conexion::setConnection(null);
                return verResutados("CORRECTO");
            }else{
                $seModel->con = null;
                Conexion::setConnection(null);
                return verResutados("ERROR");
            }
        }else
            return verResutados("INCORRECTO");
    }
    
    function verResutados($resultado){
        if($resultado == "CORRECTO"){
            $info['mensaje'] = utf8_encode("<p>¡Se ha realizado la acción correctamente, todo bien!</p>");;
            $info['titulo'] = "CORRECTO";
            $info['tipo'] = "success";
        }else if($resultado == "INCORRECTO"){
            $info['mensaje'] = "<p>Datos incorrectos</p>";
            $info['titulo'] = "INCORRECTO";
            $info['tipo'] = "warning";
        }else if($resultado == "ERROR"){
            $info['mensaje'] = "<p>No se logró realizar la acción correctamente</p>";
            $info['titulo'] = "ERROR";
            $info['tipo'] = "danger";
        }
        return $info;
    }
?>