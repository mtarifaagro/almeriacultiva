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

  require 'google-api-php-client/vendor/autoload.php';

  try {
    while ($post = each($_POST)) {
      echo $post[0] . " = " . $post[1];
    }
    
    $email     = getenv('APP_EMAIL');
    $password  = getenv('APP_EMAIL_PASSWORD');
    $mailvalid = $_POST['mailvalid'];
    $subject   = $_POST['subject'];
    $message   = 'Email enviado por ' . $mailvalid . '<br/>'
                 . 'Asunto: ' . $subject . '<br/>'
                 . $_POST['message'];
    
  //http://www.nikola-breznjak.com/blog/javascript/ionic2/posting-data-from-ionic-2-app/

    // Configuring SMTP server settings
    $mail = new PHPMailer(false);
    //$mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username = $email;
    $mail->Password = $password;
    //$mail->SMTPDebug = 2;
 
    // Email Sending Details
    $mail->setFrom($email);
    $mail->addAddress($email);
    $mail->Subject = 'Contacto Almeria Cultiva App';
    $mail->msgHTML($message);

    if (!$mail->send()){
      echo json_encode(array("status" => 1, "user" => $email, "info" => 'Mailer Error: ' . $mail->ErrorInfo));
    }else{
      echo json_encode(array("status" => 0, "info" => 'OK'));
    }
  } catch (Exception $e) {
    echo json_encode(array("status" => 2, "user" => $email, "info" => 'Mailer Error: ' . $mail->ErrorInfo));
  }
?>

