<?php 
  $servername = getenv('MYSQL_SERVICE_HOST'); 
  $database = getenv('MI_DATABASE_NAME');
  $username = getenv('MI_DATABASE_USER');
  $password = getenv('MI_DATABASE_PASSWORD');

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
