<?php 
  $servername = getenv('MYSQL_SERVICE_HOST'); 
  $database = getenv('MYSQL_DATABASE');
  $username = getenv('MYSQL_USER');
  $password = getenv('MYSQL_PASSWORD');

  echo '$servername ' . $servername . '\n' .
       '$database ' . $database . '\n' .
       '$username ' . $username . '\n' .
       '$password ' . $password;

  print_r($_ENV);
?>