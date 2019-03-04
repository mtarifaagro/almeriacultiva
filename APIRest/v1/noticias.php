<?php 
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
  header("Access-Control-Allow-Headers: X-Requested-With, Origin, Content-Type, X-Auth-Token, Authorization, Accept");
  header("Content-Type: application/json;charset=utf-8");

  include_once '../includes/configBD.php';
  include_once '../includes/authenticated.php';

  $auth = new Authenticate();

  $numRows = $auth->auth($conn);
  if ($numRows == 1){
    $result = array();

    $offSet = $_REQUEST['offSet'];

    $consulta = "SELECT * 
                 FROM ( SELECT *
                        FROM Noticias
                        ORDER BY not_id DESC) D
                  Limit 2 ";
    if ($offSet) {
      $consulta = $consulta . " OFFSET " . $offSet;
    }

    $res = $conn->query($consulta);
    while($f = $res->fetch_object()){
      $result[] = array("id" => $f->not_id,
                        "titular" => $f->not_titular, 
                        "textocorto" => $f->not_textocorto,
                        "texto" => $f->not_texto, 
                        "link" => $f->not_link,
                        "textolink" => $f->not_textolink,
                        "imagen" => $f->not_imagen,
                        "fecha" => $f->not_fecha); 
    }
    $json = array("status" => 0, "info" => $result);
 
    echo json_encode($json);
  } else {
    header('WWW-Authenticate: Basic realm="LOGIN REQUIRED"');
    header('HTTP/1.0 401 Unauthorized');
  }

?>