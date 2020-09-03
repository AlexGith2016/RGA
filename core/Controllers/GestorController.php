<?php
    $method = $_SERVER['REQUEST_METHOD'];
    
    switch ($method) {
        case 'GET':
            if(isset($_REQUEST['option'])){
                switch ($_REQUEST['option']) {
                case 1:
                     print(1);
                    break;
                default:
                    include('Views/gestor.php');
                    break;
                }
            }else
                include('Views/gestor.php');
            break;
        case 'POST':
            if(!$_POST['page']) die("0");
            
            $page = $_POST['page'];
            //echo file_get_contents('../../Views/ajax/'.$page.'.php');
            if(file_exists('../../Views/ajax/'.$page.'.php'))
                require_once('../../Views/ajax/'.$page.'.php');
            else echo '0';
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
?>