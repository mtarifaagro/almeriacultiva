<?php 
  include "class.phpmailer.php"; 
  include "class.smtp.php"; 
   
  $email_user = "m.tarifa.agro@gmail.com"; 
  $email_password = "2508Agro.5"; 
  $the_subject = "PHPmailer prueba"; 
  $address_to = "m.tarifa.agro@gmail.com"; 
  $from_name = "PHPmailer"; 
  $phpmailer = new PHPMailer(); 
   
  $phpmailer->Username = $email_user; 
  $phpmailer->Password = $email_password;  
  
  // $phpmailer->SMTPDebug = 1; 
  $phpmailer->SMTPSecure = 'ssl'; 
  $phpmailer->Host = "smtp.gmail.com"; // GMail 
  $phpmailer->Port = 465; 
  $phpmailer->IsSMTP(); // use SMTP 
  $phpmailer->SMTPAuth = true; 
   
  $phpmailer->setFrom($phpmailer->Username,$from_name); 
  $phpmailer->AddAddress($address_to); // recipients email 
   
  $phpmailer->Subject = $the_subject;	 
  $phpmailer->Body .="<h1>PHPmailer!</h1>"; 
  $phpmailer->Body .= "<p>Mensaje personalizado</p>"; 
  $phpmailer->Body .= "<p>Fecha y Hora: ".date("d-m-Y h:i:s")."</p>"; 
  $phpmailer->IsHTML(true); 
   
  $phpmailer->Send(); 
?> 