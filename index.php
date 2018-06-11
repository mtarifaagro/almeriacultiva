<?php 
  header("Access-Control-Allow-Origin: *");
  header('Access-Control-Allow-Credentials: true');
  header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
  header("Access-Control-Allow-Methods: GET, POST");
  header('Content-type: application/json');

  $json = array("status" => 1, "info" => "algo");
  echo json_encode($json);
?>