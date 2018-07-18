<?php 
$destinatario = "m.tarifa.agro@gmail.com"; 
$asunto = "Este mensaje es de prueba"; 
$cuerpo = ' 
<html> 
<head> 
   <title>Prueba de correo</title> 
</head> 
<body> 
<h1>Prueba de correo</h1> 
<p> 
<b>Prueba de correo 
</p> 
</body> 
</html> 
'; 

//para el envío en formato HTML 
$headers = "MIME-Version: 1.0\r\n"; 
$headers .= "Content-type: text/html;"; 

//dirección del remitente 
$headers .= "From: Almeria Cultiva<m.tarifa.agro@gmail.com>\r\n"; 

//dirección de respuesta, si queremos que sea distinta que la del remitente 
$headers .= "Reply-To: m.tarifa.agro@gmail.com\r\n"; 

//ruta del mensaje desde origen a destino 
//$headers .= "Return-path: m.tarifa.agro@gmail.com\r\n"; 

//direcciones que recibián copia 
//$headers .= "Cc: m.tarifa.agro@gmail.com\r\n"; 

//direcciones que recibirán copia oculta 
//$headers .= "Bcc: m.tarifa.agro@gmail.com\r\n"; 

mail($destinatario,$asunto,$cuerpo,$headers) 
?>
