<?php
  include_once 'configBD.php';

  class Authenticate{ 
       
    var $sql;

    function auth($conn){
      if (!isset($_SERVER['PHP_AUTH_USER'])) {
        return -1;
      } else{
        $usuario = $_SERVER['PHP_AUTH_USER'];
        $pass    = base64_encode($_SERVER['PHP_AUTH_PW']);
        
        $this->sql = "SELECT * FROM Usuarios WHERE usr_email = '$usuario' and usr_pass = '$pass'";
        $res2 = $conn->query($this->sql);
        
        return $res2->num_rows;
      }
    }
  }
?>
