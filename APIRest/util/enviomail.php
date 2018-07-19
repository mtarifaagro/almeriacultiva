<?php 
  //include_once 'src/phpmailer.php';
  use PHPMailer\PHPMailer\PHPMailer;
  
  $phpmailer = new PHPMailer; 
  try {
    $from_name = "Almeria Cultiva"; 
    $email_user = "m.tarifa.agro@gmail.com"; 
    $email_password = "2508Agro.5"; 
    $the_subject = "PHPmailer prueba"; 
    $address_to = "m.tarifa.agro@gmail.com"; 

    $phpmailer->Username = $email_user; 
    $phpmailer->Password = $email_password;  
  
    $phpmailer->SMTPSecure = 'ssl'; 
    $phpmailer->Host = "smtp.gmail.com";
    $phpmailer->Port = 465; 
    $phpmailer->IsSMTP();
    $phpmailer->SMTPAuth = true; 
   
    $phpmailer->setFrom($phpmailer->Username,$from_name); 
    $phpmailer->AddAddress($address_to);
   
    $phpmailer->Subject = $the_subject;	 
    $phpmailer->Body .="<h1>PHPmailer!</h1>"; 
    $phpmailer->Body .= "<p>Mensaje personalizado</p>"; 
    $phpmailer->Body .= "<p>Fecha y Hora: ".date("d-m-Y h:i:s")."</p>"; 
    $phpmailer->IsHTML(true); 
   
    $phpmailer->Send(); 
  } catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
  }
?> 