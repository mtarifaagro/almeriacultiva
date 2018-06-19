<?php 
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
  header("Access-Control-Allow-Headers: X-Requested-With, Origin, Content-Type, X-Auth-Token, Authorization, Accept");
  header("Content-Type: application/json");
  header('Cache-Control: no-cache, must-revalidate, max-age=0');

  include_once '../includes/configBD.php';
  include_once '../includes/authenticated.php';

  $auth = new Authenticate();

  $numRows = $auth->auth($conn);
  if ($numRows == 1){
    $result = array();
    $fecha = $_REQUEST['fecha'];
    $res = $conn->query("SELECT pro_id, pro_nombre, emp_id, emp_nombre, cor_corte, cor_precio
                         FROM Cortes
                         Join Precios on pre_id = cor_idpre
                         Join Productos_Empresas on pem_id = pre_pro_emp
                         Join Empresas on emp_id = pem_idemp
                         Join Productos on pro_id = pem_idpro
                         WHERE pre_fecha = '$fecha'
                         Order by pro_nombre, emp_nombre, cor_corte ");
    while($f = $res->fetch_object()){
      $result[] = array("pro_id" => $f->pro_id,
                        "producto" => $f->pro_nombre, 
                        "emp_id" => $f->emp_id,
                        "empresa" => $f->emp_nombre, 
                        "corte" => $f->cor_corte,
                        "precio" => $f->cor_precio); 
    }
    $json = array("status" => 0, "info" => $result);
 
    echo json_encode($json);
  } else {
    header('WWW-Authenticate: Basic realm="LOGIN REQUIRED"');
    header('HTTP/1.0 401 Unauthorized');
  }

?>