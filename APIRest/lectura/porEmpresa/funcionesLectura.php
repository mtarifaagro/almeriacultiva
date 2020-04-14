<?php
    header("Content-Type: text/html");

    require '../../utils/GoogleApi/PHPMailer/src/Exception.php';
    require '../../utils/GoogleApi/PHPMailer/src/PHPMailer.php';
    require '../../utils/GoogleApi/PHPMailer/src/SMTP.php';
    
    include_once '../../includes/configBD.php';
    include_once '../../includes/mail.php';

    require("../simple_html_dom.php");
    
    $ip = '154.16.202.22';
    $puerto = '8080';

    function leerAlhondiga($url, $idEmpresa, $empresa) {
        $mensaje = '';

        global $ip, $puerto, $conn;
        $sitioweb = curl($url, $ip, $puerto, $mensaje, false);

        $pos = strpos($sitioweb, 'Forbidden');
        if ($pos != false) {
            echo 'Error al leer los precios. Por favor intentelo en unos minutos.<br />' . $sitioweb . '<br />';
            exit();
        }
    
        if ($mensaje) {
            echo 'Error al leer los precios. Por favor intentelo en unos minutos.<br />' . $mensaje . '<br />';
            exit();
        }
    
        $dom = new domDocument;
        $dom->loadHTML($sitioweb);
        $dom->preserveWhiteSpace = false;

        $fechaPagina = buscarFecha($dom, "fec");

        if (empty($fechaPagina)){
            echo 'No se ha podido leer los precios. Por favor intentelo en unos minutos<br />';
            exit();
        }

        if ($fechaPagina === date("d-m-Y")){

            $tabla = buscarTabla($dom, 'sub-ver');
            $rows = $tabla->item(0)->getElementsByTagName('tr');
            $cargaOK = false;
            foreach ($rows as $row){

                $clase = $row->getAttribute('class');

                if ($clase === 'lin-0' || $clase === 'lin-1'){

                    $cols = $row->getElementsByTagName('td');
                    $etiqueta = $cols[0]->nodeValue . ' ';

                    $consulta = "SELECT pem_id
                                 FROM Productos_Empresas
                                 Join Productos on pro_id = pem_idpro
                                 Where pem_idemp = " . $idEmpresa . " and Upper(pro_etiqueta) = Upper('" . $etiqueta . "')";
                    try {
                        $resProdEmp = $conn->query($consulta);
                        $prodEmp = $resProdEmp->fetch_object();
                     } catch (mysqli_sql_exception $e) {
                        echo 'Error<br/>';
                        echo 'Excepción capturada: ', $e->getMessage(), "<br/>";
                     }

                    //echo '$prodEmp->pem_id -> ' . $prodEmp->pem_id . '<br/>';

                    $consulta = "SELECT pre_id
                                 FROM Precios
                                 Where pre_pro_emp = " . $prodEmp->pem_id . "
                                   and pre_fecha = '" . date("Y-m-d") . "'";

                    $resPrecios = $conn->query($consulta);
                    $totalFilas = $resPrecios->num_rows;

                    //echo 'totalFilas -> ' . $totalFilas . '<br/>';
                    if ($totalFilas === 0) {
                        $cargaOK = true;
                        //echo $etiqueta . ' -> ';
                        $PrecioInsertado = $conn->query("INSERT INTO Precios (pre_pro_emp, pre_fecha)
                                                         VALUES ('".$prodEmp->pem_id."', '". date("Y-m-d") ."')");

                        try {
                            $resPrecios2 = $conn->query("SELECT pre_id
                                                         FROM Precios
                                                         Where pre_pro_emp = ".$prodEmp->pem_id."
                                                           and pre_fecha = '" . date("Y-m-d") . "'");
                            $precio = $resPrecios2->fetch_object();
                        } catch (Exception $e) {
                            echo 'Error<br/>';
                            echo 'Excepción capturada: ', $e->getMessage(), "<br/>";
                        }

                        if ($precio->pre_id > 0) {
                            $numCorte = 0;
                            foreach ($cols as $col){
                                if ($numCorte === 0){
                                    $numCorte ++;
                                } elseif ($col->nodeValue !== "") {
                                    //echo $col->nodeValue . ' - ';
                                    $conn->query("INSERT INTO Cortes (cor_idpre, cor_corte, cor_precio)
                                                  VALUES ('".$precio->pre_id."', '".$numCorte."', '".$col->nodeValue."')");
                                    $numCorte ++;
                                }
                            }
                             //echo '<br/>';
                        }

                        $conn->query("UPDATE Precios
                                      SET pre_media3 = (Select avg(cor_precio)
                                                        From Cortes
                                                        Where cor_corte <= 3
                                                        and cor_idpre = pre_id )
                                      Where pre_media3 is null");
                        //echo '<br />';
                    }
                }
            }
             
            enviarMail($cargaOK);
            
        } else {
            echo 'Los precios leidos son del ' . $fechaPagina . '. Aseguresé que hay subasta y que los precios están cargados.<br/>';
        }
    }

    function enviarMail($cargaOK) {
        if ($cargaOK){
            echo 'La carga de precios se ha fectuado correctamente.<br/>';
            $resp = enviarMailCargaPrecios($url, $empresa, 'Los precios de '. $empresa .' se han cargado correctamente.');
            echo $resp;
        } else {
            echo 'Los precios ya estan cargados.<br/>';
        }
    }

    function leerAlhondiga2($url, $idEmpresa, $empresa) {
        $mensaje = '';

        global $ip, $puerto, $conn;
        $sitioweb = curl($url, $ip, $puerto, $mensaje, true);
   
        $pos = strpos($sitioweb, 'Forbidden');
        if ($pos != false) {
            echo 'Error al leer los precios. Por favor intentelo en unos minutos.<br />' . $sitioweb . '<br />';
            exit();
        }
    
        if ($mensaje) {
            echo 'Error al leer los precios. Por favor intentelo en unos minutos.<br />' . $mensaje . '<br />';
            exit();
        }
    
        $dom = new domDocument;
        $dom->loadHTML($sitioweb);
        $dom->preserveWhiteSpace = false;
    
        $fechaPagina = buscarFecha($dom, "titNombreder");
    
        if ($fechaPagina){
            echo 'No se ha podido leer los precios. Por favor intentelo en unos minutos<br />';
            exit();
        }
   
        if ($fechaPagina === date("d-m-Y")){  
            $tabla = buscarTabla($dom, 'precios_pro');
            $rows = $tabla->item(1)->getElementsByTagName('tr');
            $cargaOK = false;
            foreach ($rows as $row){
    
                $clase = $row->getAttribute('class');
                //echo $clase . '<br />';
    
                if ($clase != 'familias_subasta' ){
    
                    $cols = $row->getElementsByTagName('td');
                    $etiqueta = $cols[0]->nodeValue;
    
                    $consulta = "SELECT pem_id
                                 FROM Productos_Empresas
                                 Join Productos on pro_id = pem_idpro
                                 Where pem_idemp = " . $idEmpresa . " and Upper(pro_etiqueta) = Upper('" . $etiqueta . "')";
    
                    //echo $consulta . '<br/>';
                    try {
                        $resProdEmp = $conn->query($consulta);
                        $prodEmp = $resProdEmp->fetch_object();
                     } catch (Exception $e) {
                        echo 'Error<br/>';
                        echo 'Excepción capturada: ', $e->getMessage(), "<br/>";
                     }
    
                    //echo '$prodEmp->pem_id -> ' . $prodEmp->pem_id . '<br/>';
    
                    $consulta = "SELECT pre_id
                                 FROM Precios
                                 Where pre_pro_emp = " . $prodEmp->pem_id . "
                                   and pre_fecha = '" . date("Y-m-d") . "'";
    
                    $resPrecios = $conn->query($consulta);   
                    $totalFilas = $resPrecios->num_rows;
                    //echo 'totalFilas -> ' . $totalFilas . '<br/>';
                    if ($totalFilas === 0) {
                        $cargaOK = true;
                        //echo $etiqueta . ' -> ';
                        $PrecioInsertado = $conn->query("INSERT INTO Precios (pre_pro_emp, pre_fecha)
                                                         VALUES ('".$prodEmp->pem_id."', '". date("Y-m-d") ."')");
    
                        try {
                            $resPrecios2 = $conn->query("SELECT pre_id
                                                         FROM Precios
                                                         Where pre_pro_emp = ".$prodEmp->pem_id."
                                                           and pre_fecha = '" . date("Y-m-d") . "'");
                            $precio = $resPrecios2->fetch_object();
                        } catch (Exception $e) {
                            echo 'Error<br/>';
                            echo 'Excepción capturada: ', $e->getMessage(), "<br/>";
                        }
    
                        if ($precio->pre_id > 0) {
                            $numCorte = 0;
                            foreach ($cols as $col){
                                if ($numCorte === 0){
                                    $numCorte ++;
                                } elseif ($col->nodeValue !== "-") {
                                     //echo $col->nodeValue . ' - ';
                                    $conn->query("INSERT INTO Cortes (cor_idpre, cor_corte, cor_precio)
                                                   VALUES ('".$precio->pre_id."', '".$numCorte."', '".$col->nodeValue."')");
                                    $numCorte ++;
                                }
                            }
                            //echo '<br/>';
                        }
    
                        $conn->query("UPDATE Precios
                                      SET pre_media3 = (Select avg(cor_precio)
                                                        From Cortes
                                                        Where cor_corte <= 3
                                                        and cor_idpre = pre_id )
                                      Where pre_media3 is null");
                        //echo '<br />';
                     
                        $numCorte = 0;
                        foreach ($cols as $col){
                            if ($numCorte === 0){
                                $numCorte ++;
                            } elseif ($col->nodeValue !== "") {
                                //echo $col->nodeValue . ' - ';
                                $conn->query("INSERT INTO Cortes (cor_idpre, cor_corte, cor_precio)
                                             VALUES ('".$precio->pre_id."', '".$numCorte."', '".$col->nodeValue."')");
                                 $numCorte ++;
                            }
                        }
                        //echo '<br/>';
                    }
                }
            }
    
            enviarMail($cargaOK);

        } else {
            echo 'Los precios leidos son del ' . $fechaPagina . '. Aseguresé que hay subasta y que los precios están cargados.<br/>';
        }
    }

    function curl($url, $ip, $puerto, &$error, $withProxy) {

        $proxy = $ip.':'.$puerto;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        //if ($withProxy)
        //  curl_setopt($ch, CURLOPT_PROXY, $proxy);

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_MAXREDIRS , 10000);

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
?>
<html xml:lang="es-ES" lang="es-ES">
  <head>
  </head>
  <body>
    <button onclick="location.href='http://api.almeriacultiva.com/lectura/porEmpresa/lectura.php'">Volver</button>
    <br><br>
  </body>
</html>