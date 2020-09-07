<?php
    use Models\UsuarioModel;
    use Models\Conexion;
    use Entities\Usuario;
    use Entities\Rol;
    
    $method = $_SERVER['REQUEST_METHOD'];
    
    switch ($method) {
        case 'GET':
            if(isset($_REQUEST['option'])){
                switch ($_REQUEST['option']) {  
                case 1:
                    terminarSesion();
                    header("Location:../../index.php");
                    break;
                default:
                    include('Views/sesion.php');
                    break;
                }
            }else{
                include('Views/sesion.php');
                break;
            }
            break;
        case 'POST':
            require_once("../Models/UsuarioModel.php");
            $post_vars = $_POST;
            verificar_login($post_vars);   
            break;
        case 'PUT':
            require_once("../Models/UsuarioModel.php");
            parse_str(file_get_contents('php://input'), $put_vars);  
            cambiar($put_vars);
            break;
        case 'DELETE': 
            print("DELETE\n");
            parse_str(file_get_contents('php://input'), $delete_vars);  
            var_dump($delete_vars);
            break;
    }
    exit();
    
    function terminarSesion(){
        session_cache_limiter('nocache');
        $cache_limiter = session_cache_limiter();
        session_cache_expire(0);
        $cache_expire = session_cache_expire();
        session_start();
        session_destroy();
        session_write_close();
    }
    
    function verificar_login($post_vars){
        $usuarioModel = new UsuarioModel();
        $usuario = new Usuario();
        $usuario->setnombreUsuario(trim($post_vars["userTXT"]));
        $usuario->setcontra(trim($post_vars["passTXT"]));
        $valid = $usuario->validarDatos();
        if($valid){
            $cantidad = $usuarioModel->buscarUsuario($usuario);
            if($cantidad == 1){
                $now =  date("m/Y");
                if(trim($post_vars["token"]) == "2020"){
                    $result = $usuarioModel->results->fetch(PDO::FETCH_ASSOC);
                    $usuario->m_Rol = new Rol();
                    $usuario->m_Rol->setnombreRol($result["nombreRol"]);
                    $usuario->m_Rol->setRolID($result["rolID"]);
                    session_set_cookie_params(0);
                    session_cache_limiter('nocache');
                    $cache_limiter = session_cache_limiter();
                    session_cache_expire(0);
                    $cache_expire = session_cache_expire();
                    session_start();
                    $_SESSION["usuario"] = serialize($usuario);
                    $usuarioModel->con = null;
                    Conexion::setConnection(null);
                    $m['mensaje'] = "OK";
                    echo json_encode($m, JSON_FORCE_OBJECT);
//                    exit();
                }else{
                    $usuarioModel->con = null;
                    Conexion::setConnection(null);
                    $m['mensaje'] = "<p>Los datos escritos con Inconrrectos</p>";
                    $m['titulo'] = "INCORRECTO";
                    $m['tipo'] = "danger";
                    echo json_encode($m, JSON_FORCE_OBJECT);
                    exit();
                }
            }else if($cantidad == 0){
                $usuarioModel->con = null;
                Conexion::setConnection(null);
                $m['mensaje'] = "<p>Los datos escritos con Inconrrectos</p>";
                $m['titulo'] = "INCORRECTO";
                $m['tipo'] = "danger";
                echo json_encode($m, JSON_FORCE_OBJECT);
                exit();
            }else if($cantidad == -1){
                $usuarioModel->con = null;
                Conexion::setConnection(null);
                $m['mensaje'] = "<p>No se logró realizar la acción de inicio de sesión</p>";
                $m['titulo'] = "ERROR";
                $m['tipo'] = "warning";
                echo json_encode($m, JSON_FORCE_OBJECT);
                exit();
            }
        }
    }
    
    function cambiar($put_vars){
        $usuarioModel = new UsuarioModel();
        $usuario = new Usuario();
        $usuario->setnombreUsuario(trim($put_vars["userTXTA"]));
        $usuario->setcontra(trim($put_vars["passTXTA"]));
        $valid = $usuario->validarDatos();
        if($valid){
            $contraR = trim($put_vars["passTXTR"]);
            $contraN = trim($put_vars["passTXTN"]);
            $nombreU = trim($put_vars["userTXT"]);
            if(isset($contraR) && !empty($contraR) && strlen($contraR) <= 30 && isset($nombreU) && !empty($nombreU) && 
                strlen($nombreU) <= 30 && isset($contraR) && !empty($contraR) && strlen($contraR) <= 30 && $contraN == $contraR)
                $valid = true;
            else
                $valid = false;
        }
        if($valid){
            $contraN = addslashes($contraN);
            $nombreU = addslashes($nombreU);
            $cantidad = $usuarioModel->buscarUsuario($usuario);
            if($cantidad == 1){
                $usuario->setcontra($contraN);
                $r = $usuarioModel->actualizarDatos($usuario, $nombreU);
                if($r > 0){
                    terminarSesion();
                    $usuarioModel->con = null;
                    Conexion::setConnection(null);
                    $m['mensaje'] = "OK";
                    echo json_encode($m, JSON_FORCE_OBJECT);
                    exit();
                }else{
                    $usuarioModel->con = null;
                    Conexion::setConnection(null);
                    $m['mensaje'] = "<p>No se logró realizar la acción de inicio de sesión</p>";
                    $m['titulo'] = "ERROR";
                    $m['tipo'] = "warning";
                    echo json_encode($m, JSON_FORCE_OBJECT);
                    exit();
                }
            }else if($cantidad == 0){
                $usuarioModel->con = null;
                Conexion::setConnection(null);
                $m['mensaje'] = "<p>Los datos escritos con Inconrrectos</p>";
                $m['titulo'] = "INCORRECTO";
                $m['tipo'] = "danger";
                echo json_encode($m, JSON_FORCE_OBJECT);
                exit();
            }else if($cantidad == -1){
                $usuarioModel->con = null;
                Conexion::setConnection(null);
                $m['mensaje'] = "<p>No se logró realizar la acción de inicio de sesión</p>";
                $m['titulo'] = "ERROR";
                $m['tipo'] = "warning";
                echo json_encode($m, JSON_FORCE_OBJECT);
                exit();
            }
        }else{
            $m['mensaje'] = "<p>Los datos escritos con Inconrrectos</p>";
            $m['titulo'] = "INCORRECTO";
            $m['tipo'] = "danger";
            echo json_encode($m, JSON_FORCE_OBJECT);
            exit();
        }
    }
?>