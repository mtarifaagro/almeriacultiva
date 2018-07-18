<?php 
$myEmail = "m.tarifa.agro@gmail.com"; 
$asunto = "Este mensaje es de prueba"; 

// make sure you get these SMTP settings right
$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "ssl") 
    ->setUsername($myEmail)
    ->setPassword('2508Agro.5');

$mailer = Swift_Mailer::newInstance($transport);

// the message itself
$message = Swift_Message::newInstance('email subject')
    ->setFrom(array('noreply@AlmeriaCultiva.com' => 'no reply'))
    ->setTo(array($myEmail))
    ->setBody("email body");

$result = $mailer->send($message);

?>
