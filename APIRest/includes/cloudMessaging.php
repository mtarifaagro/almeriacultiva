<?php

    enviarNotificacion('precios', 'Precios Subasta '.date("d-m-Y"), 'Ya hemos terminado de cargar los precios de hoy. Consúltalos!!');

    function enviarNotificacion($topic, $title, $message) {

        // Cargamos los datos de la notificacion en un Array
        $notification = array();
        $notification['title'] = $title;
        $notification['message'] = $message;
        $notification['image'] = '';
        $notification['action'] = '';
        $notification['action_destination'] = '';            
    
        $fields = array(
            'to' => '/topics/' . $topic,
            //'registration_ids' => array ($id),
            'data' => $notification,
        );
    
        // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';
    
        $headers = array(
                    'Authorization: key=AAAAcMYfKf0:APA91bGn19pWPdNw7nZLZr-aRFsXjU-SKR1ysohqc0hU1Eep5uVdVvwvwCIxz9yGfTnC4CUctWarhNe8Aw-Cc199GAJUsPf0unKb_KQ8ZLxdxwpwF9gfgAYl7PPWm7NvAmQgDVYZ2yeb',
                    'Content-Type: application/json'
                    );
                    
        // Open connection
        $ch = curl_init();
    
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
    
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        // Disabling SSL Certificate support temporarily
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));       
        
        $result = curl_exec($ch);
        echo $result;
    
        if($result === FALSE) {
    
            die('Curl failed: ' . curl_error($ch));
        }
    
        // Close connection
        curl_close($ch);
    }
?>