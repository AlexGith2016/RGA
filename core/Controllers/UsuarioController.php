<?php
    use Models\UsuarioModel;
    use Models\Conexion;
    
    $method = $_SERVER['REQUEST_METHOD'];
    
    require_once("../Models/UsuarioModel.php");
    require_once("../Models/Conexion.php");
    
    switch ($method) {
        case 'GET':
            print("GET\n");
            parse_str(file_get_contents('php://input'), $delete_vars);  
            var_dump($delete_vars);
            break;
            /*switch ($_REQUEST['opcion']) {
                case 1:
                    cargarUsuarios();
                    break;
                default:
                    print('UsuarioController no hay opcion');
                    break;
            }*/
            break;
        case 'POST':
            /*parse_str(file_get_contents('php://input'), $post_vars);
            $m = array('mensaje' => '');
            if(isset($post_vars['opcion']) && isset($post_vars['nombreCompleto']) && !empty($post_vars['nombreCompleto']) &&
                isset($post_vars['correo']) && !empty($post_vars['correo']) && isEmail($post_vars['correo']) &&
                isset($post_vars['telefono']) && !empty($post_vars['telefono']) && is_numeric($post_vars['telefono'])){
                
                $post_vars['telefono'] = ( int ) $post_vars['telefono'];
                $post_vars['nombreCompleto'] = addslashes($post_vars['nombreCompleto']);
                switch ($post_vars['opcion']) {
                    case "estudiante":
                        $m['mensaje'] = 'CORRECTO';
                        break;
                    case "donante":
                        $m['mensaje'] = 'CORRECTO';
                        break;
                    case "empresa":
                        $m['mensaje'] = 'CORRECTO';
                        break;
                    default:
                        $m['mensaje'] = 'VACIO';
                        break;
                }
                echo json_encode($m, JSON_FORCE_OBJECT);
            }else{
                $m['mensaje'] = 'INCORRECTO';
                echo json_encode($m, JSON_FORCE_OBJECT);
            }
            exit();*/
            break;
        case 'PUT':
            print("PUT\n");
            parse_str(file_get_contents('php://input'), $put_vars);  
            var_dump($put_vars);
            break;
        case 'DELETE': 
            print("DELETE\n");
            parse_str(file_get_contents('php://input'), $delete_vars);  
            var_dump($delete_vars);
            break;
    }
    exit();
    
    function cargarUsuarios(){
        $usuarioModel = new UsuarioModel();
        $resultados = $usuarioModel->listarUsuarios();
        if(isset($resultados)){
            $usuarioModel->con = null;
            Conexion::setConnection(null);
            foreach ($resultados as $value) {
                $arreglo["data"][] = $value;
            }
            echo json_encode($arreglo);
        }else
            print("error al cargar usuarios o no hay registros");
        exit();
    }
?>