<?php
  include_once 'configBD.php';

  class Authenticate{ 
       
    var $sql;

    function auth($conn){
      if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $ha = base64_decode( substr($_SERVER['HTTP_AUTHORIZATION'],6) );
        list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':', $ha);
        unset $ha;
      }

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
