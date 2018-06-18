<?php 
  $servername = getenv('MYSQL_SERVICE_HOST'); 
  $username = 'user6UN'; 
  $password = 'uGfGbYo1BaOabiw2'; 
  $database = 'sampledb'; 

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
