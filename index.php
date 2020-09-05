<?php
    $method = $_SERVER['REQUEST_METHOD'];
    $view = isset($_GET['view']) ? $_GET['view'] : 'Index';
    
    if (file_exists('core/Controllers/'.$view.'Controller.php')) {
        include('core/Controllers/'.$view.'Controller.php');
    }else{
        //page Error
        include('Views/404.php');
    }
?>