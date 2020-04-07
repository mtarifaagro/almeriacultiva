<?php  
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
  header("Access-Control-Allow-Headers: X-Requested-With, Origin, Content-Type, X-Auth-Token, Authorization, Accept");
  header("Content-Type: application/json");

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  require 'PHPMailer/src/Exception.php';
  require 'PHPMailer/src/PHPMailer.php';
  require 'PHPMailer/src/SMTP.php';

  //require 'google-api-php-client/vendor/autoload.php';

  try {
    $json = file_get_contents('php://input');
    $params = json_decode($json);
    
    $parameros = 'parametros: ' . $params->mailvalid . ' - ' . $params->subject . ' - ' . $params->message;

    $email     = 'contacto@almeriacultiva.com';
    $password  = '2508Migue5';
    $mailvalid = $params->mailvalid;
    $subject   = $params->subject;
    $message   = '   Gracias por contactar con nosotros.'
                 . '   En la mayor brevedad posible le contestaremos a la direccion indicada (' . $mailvalid . ')<br/><br/>'
                 . 'Su mensaje:<br/>'
                 . '<i>"' . $params->message . '"</i><br/><br/>'
                 . 'Equipo de Almeria Cultiva <br>'
                 . 'contacto@almeriacultiva.com';

    // Configuring SMTP server settings
    $mail = new PHPMailer(false);
    //$mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'ssl0.ovh.net';
    $mail->Port = 465;
    $mail->SMTPSecure = 'ssl';
    $mail->SMTPAuth = true;
    $mail->Username = $email;
    $mail->Password = $password;
    //$mail->SMTPDebug = 4;
 
    // Email Sending Details
    $mail->setFrom($email, 'Almeria Cultiva');
    $mail->addAddress($mailvalid);
    $mail->addBCC($email);
    $mail->Subject = 'Ha contactado con Almeria Cultiva ('.$subject.')';
    $mail->msgHTML($message);

    if (!$mail->send()){
      echo json_encode(array("status" => 1, "user" => $email, "info" => 'Mailer Error: ' . $mail->ErrorInfo));
    }else{
      echo json_encode(array("status" => 0, "info" => $parameros));
    }
  } catch (Exception $e) {
    echo json_encode(array("status" => 2, "info" => 'Mailer Error: ' . $mail->ErrorInfo));
  }
?>