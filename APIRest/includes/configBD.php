<?php 
  $servername = 'almeriaclnbd.mysql.db'; 
  $database = 'almeriaclnbd';
  $username = 'almeriaclnbd';
  $password = '2508Migue5';

  try {
    $conn = new mysqli($servername, $username, $password, $database);
    $conn->set_charset("utf8");

    if ($conn->connect_error) {
      $json = array("status" => 1, "info" => $conn->connect_error);
      echo json_encode($json);
    }
  } catch (Exception $e) {
    $json = array("status" => 1, "info" => $e->getMessage());
    echo json_encode($json);
  }

?>
