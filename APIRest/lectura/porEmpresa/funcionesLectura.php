<?php
    header("Content-Type: text/html");

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require '../../utils/GoogleApi/PHPMailer/src/Exception.php';
    require '../../utils/GoogleApi/PHPMailer/src/PHPMailer.php';
    require '../../utils/GoogleApi/PHPMailer/src/SMTP.php';

    function curl($url, &$error) {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_PROXY, '80.187.140.26:8080');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_MAXREDIRS , 1000);

        $info = curl_exec($ch);
        $error = curl_error($ch);

        if ($error)
            $error = $error . ' - ' . curl_error($ch);

         curl_close($ch);

        return $info;
    }

    function buscarFecha($dom, $classname){

        $finder = new DomXPath($dom);
        $nodes = $finder->query("//*[contains(@class, '$classname')]");

        foreach ($nodes as $node){
            $miFecha = $node->nodeValue;
        }

        $miFecha = html_entity_decode($miFecha);
        $miFecha = preg_replace("/\s/",'',$miFecha);
        $miFecha = htmlentities($miFecha, null, 'utf-8');
        $miFecha = str_replace("&nbsp;", "", $miFecha);

        return $miFecha;
    }

    function buscarTabla($dom, $clasename){
        $div = $dom->getElementById($clasename);
        $tables = $div->getElementsByTagName('table');

        return $tables;
    }

    function enviarMail($url, $empresa, $mensaje){

        try {
            $email     = 'contacto@almeriacultiva.com';
            $password  = '2508Migue5';
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
            $mail->Username = $email;
            $mail->Password = $password;
            //$mail->SMTPDebug = 4;

            // Email Sending Details
            $mail->setFrom($email, 'Almeria Cultiva');
            $mail->addAddress($email);
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
<html xml:lang="es-ES" lang="es-ES">
  <head>
  </head>
  <body>
    <button onclick="location.href='http://api.almeriacultiva.com/lectura/porEmpresa/lectura.php'">Volver</button>
    <br><br>
  </body>
</html>