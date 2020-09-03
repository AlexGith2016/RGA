<?php
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        case 'GET':
            if($_REQUEST != null){
              switch ($_REQUEST['option']) {
                  case 1:
                       print(1);
                      break;
                  default:
                      include('Views/home.php');
                      break;
              }
            }else {
              include('Views/home.php');
            }
            break;
        case 'POST':
            print("POST\n");
            parse_str(file_get_contents('php://input'), $post_vars);
            var_dump($post_vars);
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
?>
