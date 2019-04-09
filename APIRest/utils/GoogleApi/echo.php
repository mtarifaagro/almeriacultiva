<?php
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
  header("Access-Control-Allow-Headers: X-Requested-With, Origin, Content-Type, X-Auth-Token, Authorization, Accept");
  header("Content-Type: application/json");
    

    $json = file_get_contents('php://input');
    $params = json_decode($json);

    $variable = 'parametros: ' . $params->mailvalid . ' - ' . $params->subject . ' - ' . $params->message;
    if ($_POST) {
        $descripcion = $_POST['descripcion'];
        $titulo = $_POST['titulo'];
        $variable = 'post';
        //echo json_encode(array('message' => $descripcion . ' - ' . $titulo));    

    }    
    if ($_GET) {
        $variable = 'get';
        //echo json_encode(array('message' => $descripcion . ' - ' . $titulo));    

    }    
    echo json_encode(array('message' => $variable));
    /*
    $posted = file_get_contents("php://input");
    $obj = json_decode($posted, TRUE);

    $descripcion =  strip_tags($obj->descripcion);
    $titulo      =  strip_tags($obj->titulo);

    //echo json_encode(array('message' => $descripcion . ' - ' . $titulo));
    echo json_encode(array('message' => $obj));
    */
?>