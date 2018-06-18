<?php 
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
  header("Access-Control-Allow-Headers: X-Requested-With, Origin, Content-Type, X-Auth-Token, Authorization, Accept");
  header("Content-Type: application/json");

  include_once '../includes/configBD.php';
  include_once '../includes/authenticated.php';

  echo 'PHP_AUTH_USER -> ' . $_SERVER['PHP_AUTH_USER'];
  echo ' - PHP_AUTH_PW -> ' . $_SERVER['PHP_AUTH_PW'];

  $auth = new Authenticate();

  $numRows = $auth->auth($conn);
  if ($numRows == 1){
    $result = array();
    $res = $conn->query("SELECT emp_id, emp_nombre, emp_imagen
                         FROM Empresas
                         Where emp_activo = 'Y' ");
    while($f = $res->fetch_object()){
      $result[] = array("id" => $f->emp_id, 
                        "nombre" => $f->emp_nombre, 
                        "imagen" => $f->emp_imagen); 
    }
    $json = array("status" => 0, "info" => $result);
 
    echo json_encode($json);
  } else {
    header('WWW-Authenticate: Basic ""');
    header('HTTP/1.0 403 Unauthorized');
  }

?>
