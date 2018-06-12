<?php
  echo 
    $_ENV['OPENSHIFT_MYSQL_DB_HOST'] + '\r\n' + 
    $_ENV['OPENSHIFT_MYSQL_DB_USERNAME'] + '\r\n' + 
    $_ENV['OPENSHIFT_MYSQL_DB_PASSWORD'] + '\r\n' + 
    $_ENV['OPENSHIFT_APP_NAME'] + '\r\n' + 
    $_ENV['OPENSHIFT_MYSQL_DB_PORT'] + '\r\n' + 
    $_ENV['OPENSHIFT_MYSQL_DB_SOCKET'];
?>