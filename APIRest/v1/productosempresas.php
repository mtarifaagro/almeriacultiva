<?php
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
  header("Access-Control-Allow-Headers: X-Requested-With, Origin, Content-Type, X-Auth-Token, Authorization, Accept");
  header("Content-Type: application/json");

  include_once '../includes/configBD.php';
  include_once '../includes/authenticated.php';

  $auth = new Authenticate();

  $numRows = $auth->auth($conn);
  if ($numRows == 1){
    $result = array();
    $res = $conn->query("SELECT pem_id, pem_idpro, pem_idemp
                         FROM Productos_Empresas 
                         Where  pem_activo = 'Y' ");
    while($f = $res->fetch_object()){
      $result[] = array("id" => $f->pem_id, 
                        "producto" => $f->pem_idpro, 
                        "empresa" => $f->pem_idemp); 
    }
    $json = array("status" => 0, "info" => $result);
 
    echo json_encode($json);
  } else {
    header('WWW-Authenticate: Basic realm="LOGIN REQUIRED"');
    header('HTTP/1.0 401 Unauthorized');
  }

?>