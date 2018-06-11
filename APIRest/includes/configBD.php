<?php
/* 
  //000webhost
  $servername = "localhost";
  $username = "id5661954_agroprecios";
  $password = "2508Agro.5";
  $database = "id5661954_ap1";
*/
  //x10Hosting
  $servername = "localhost";
  $username = "almeriac_miguel";
  $password = "2508Agro.5";
  $database = "almeriac_01";

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
