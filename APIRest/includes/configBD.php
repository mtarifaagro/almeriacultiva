<?php 
  $servername = getenv('MYSQL_SERVICE_HOST'); 
  $database = 'sampledb'; 
  $username = 'userF04'; 
  $password = 'DTbnv5R63jiVihVS'; 

  //$database = getenv('MYSQL_DATABASE');
  //$username = getenv('MYSQL_USER');
  //$password = getenv('MYSQL_PASSWORD');

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
