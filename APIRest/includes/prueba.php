<?php 
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
  header("Access-Control-Allow-Headers: X-Requested-With, Origin, Content-Type, X-Auth-Token, Authorization, Accept");
  header("Content-Type: application/json");
  
  $servername = getenv('MYSQL_SERVICE_HOST'); 
  $database = getenv('MI_DATABASE_NAME');
  $username = getenv('MI_DATABASE_USER');
  $password = getenv('MI_DATABASE_PASSWORD');

  echo '$servername ' . $servername;
  echo '$database ' . $database;
  echo '$username ' . $username;
  echo '$password ' . $password;

  print_r($_ENV);
?>
