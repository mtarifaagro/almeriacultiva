<?php 
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
