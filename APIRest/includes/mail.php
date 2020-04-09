<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

    $emailContacto     = 'contacto@almeriacultiva.com';
    $passwordContacto  = '2508Migue5';

    function enviarMailCargaPrecios($url, $empresa, $mensaje){

        global $emailContacto, $passwordContacto;
        
        try {
            $message   = $mensaje
                         . '<br/><br/>'
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
            $mail->Username = $emailContacto;
            $mail->Password = $passwordContacto;
            //$mail->SMTPDebug = 4;
    
            // Email Sending Details
            $mail->setFrom($emailContacto, 'Almeria Cultiva');
            $mail->addAddress($emailContacto);
            $mail->Subject = 'Carga de precios ' . $empresa . ' (' . date("Y-m-d") . ')';
            $mail->msgHTML($message);
    
            if (!$mail->send()){
                return $mail->ErrorInfo;
             }else{
                return 'email ok <br/>';
             }
    
         } catch (Exception $e) {
            return $mail->ErrorInfo;
         }
     }
?>