<?php
    include_once '../includes/configBD.php';
    include_once '../includes/authenticated.php';

    $auth = new Authenticate();    
    $numRows = $auth->auth($conn);

    if ($numRows == 1){

        if($_FILES["archivo"]["size"]>1000000){
           echo "Solo se permiten archivos menores de 1MB";
         }else{
            // sacamos todas las propiedades del archivo
            $nombre_archivo = $_FILES['archivo']['name'];
            $tipo_archivo= $_FILES['archivo']['type'];
            $tamano_archivo = $_FILES["archivo"]['size'];
            $direccion_temporal = $_FILES['archivo']['tmp_name'];
            // movemos el archivo a la capeta de nuestro servidor
            move_uploaded_file($_FILES['archivo']['tmp_name'],"".$_FILES['archivo']['name']);
         }

        $arrayProductos = array();
        $Fecha = date("Y-m-d");

        $fila = 1;
        if (($gestor = fopen($nombre_archivo,"r"))!== FALSE) {
            while (($data = fgetcsv($gestor,1000,";")) !== FALSE) {
                if ($data[1] > 0){   
                    
                    $resPrecios = $conn->query("SELECT pre_id
                                                FROM Precios 
                                                Where pre_pro_emp = ".$data[0]." 
                                                  and pre_fecha = '" . $Fecha . "'");
                    $totalFilas = $resPrecios->num_rows;
                    if ($totalFilas === 0) {
                        $PrecioInsertado = $conn->query("INSERT INTO Precios (pre_pro_emp, pre_fecha) 
                                                           VALUES ('".$data[0]."', '".$Fecha."')");
                        try {
                            $resPrecios2 = $conn->query("SELECT pre_id
                                                         FROM Precios 
                                                         Where pre_pro_emp = ".$data[0]." 
                                                           and pre_fecha = '".$Fecha."'");
                            $precio = $resPrecios2->fetch_object();
                        
                            $contador = 0;                
                            foreach ($data as $datoCol) {        
                                if ($contador > 0) {
                                    if ($datoCol > 0){
                                        $conn->query("INSERT INTO Cortes (cor_idpre, cor_corte, cor_precio) 
                                                        VALUES ('".$precio->pre_id."', '".$contador."', '".$datoCol."')");
                                     }
                                 }
                                $contador++;
                             }
                         } catch (Exception $e) {
                            echo 'Excepción capturada: ', $e->getMessage(), "\n";
                         }
                     }
                 }
             }
            fclose($gestor);
            unlink($nombre_archivo);

            $conn->query("UPDATE Precios
                          SET pre_media3 = (Select avg(cor_precio)
                                            From Cortes
                                            Where cor_corte <= 3
                                              and cor_idpre = pre_id )
                          Where pre_media3 is null");
         }
     }
?>