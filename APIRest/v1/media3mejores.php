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
    $lstproemp = $conn->query("SELECT pem_id, pem_idemp, pem_idpro
                               FROM Productos_Empresas
                               WHERE pem_activo = 'Y' ");
    while($proemp = $lstproemp->fetch_object()){
      
      $lstprecio = $conn->query("SELECT pre_id, pre_fecha
                                 FROM Precios
                                 WHERE pre_pro_emp = ".$proemp->pem_id."
                                 ORDER BY pre_fecha LIMIT 7");
      while($precio = $lstprecio->fetch_object()){
      
        $lstmedia = $conn->query("SELECT AVG(cor_precio) media
                                   FROM ( SELECT cor_precio
                                          FROM Cortes
                                          WHERE cor_idpre = ".$precio->pre_id."
                                          ORDER BY cor_corte LIMIT 3 ) C");
        $media = $lstmedia->fetch_object();
        $result[] = array("hortaliza" => $proemp->pem_idpro, 
                          "empresa" => $proemp->pem_idemp, 
                          "fecha" => $precio->pre_fecha, 
                          "media" => $media->media);
      }
    }
    $json = array("status" => 0, "info" => $result);
 
    echo json_encode($json);
  } else {
    header('WWW-Authenticate: Basic realm="LOGIN REQUIRED"');
    header('HTTP/1.0 401 Unauthorized');
  }

?>