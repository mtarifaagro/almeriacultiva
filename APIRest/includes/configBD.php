<?php
  $servername = getenv('MYSQL_SERVICE_HOST');
  $username = getenv('MYSQL_USER');
  $password = getenv('MYSQL_PASSWORD');
  $database = getenv('MYSQL_DATABASE');

  try {
    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
      $json = array("status" => 1, "info" => $conn->connect_error);
      echo json_encode($json);
    }
  } catch (Exception $e) {
    $json = array("status" => 1, "info" => $e->getMessage());
    echo json_encode($json);
  }

?>
