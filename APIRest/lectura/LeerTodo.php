<?php 
    require("simple_html_dom.php"); 

    include_once '../includes/configBD.php';
    include_once '../includes/authenticated.php';
  
    $auth = new Authenticate();
    $arrayProductos = array();

    $Fecha = date("Y-m-d");

    $numRows = $auth->auth($conn);
    if ($numRows == 1){
        $resProductos = $conn->query("Select pro_id, pro_nombre, pro_urlupd 
                                      From Productos");
        while($f = $resProductos->fetch_object()){

            $html=file_get_contents($f->pro_urlupd); 

            $dom = new domDocument; 
            $dom->loadHTML($html); 
            $dom->preserveWhiteSpace = false; 
        
            $div = $dom->getElementById('pro-ver'); 
            $tables = $div->getElementsByTagName('table'); 

            $classname="fec";
            $finder = new DomXPath($dom);
            $nodes = $finder->query("//*[contains(@class, '$classname')]");

            foreach ($nodes as $node) 
            {
                $miFecha = $node->nodeValue;
            }

            $miFecha = html_entity_decode($miFecha);
            $miFecha = preg_replace("/\s/",'',$miFecha);
            $miFecha = htmlentities($miFecha, null, 'utf-8');
            $miFecha = str_replace("&nbsp;", "", $miFecha);

            if ($miFecha === date("d-m-Y")){

                $rows = $tables->item(0)->getElementsByTagName('tr'); 
        
                $contador = 0;
                foreach ($rows as $row) 
                {        
                    if ($contador !== 0){
                        $cols = $row->getElementsByTagName('td'); 
                        
                        if ($cols->item(0)->nodeValue !== "SUBASTAS" && $cols->item(1)->nodeValue !== ""){
                            $resProdEmp = $conn->query("SELECT pem_id 
                                                        FROM Productos_Empresas 
                                                        Join Empresas on emp_id = pem_idemp
                                                        Where pem_idpro = ".$f->pro_id." 
                                                          and Upper(emp_etiqueta) = Upper('".$cols->item(0)->nodeValue."')");
                            $prodEmp = $resProdEmp->fetch_object();
    
                            $resPrecios = $conn->query("SELECT pre_id
                                                        FROM Precios 
                                                        Where pre_pro_emp = ".$prodEmp->pem_id." 
                                                          and pre_fecha = '" . $Fecha . "'");
                                                          
                            $totalFilas = $resPrecios->num_rows;
                            if ($totalFilas === 0) {
                                $PrecioInsertado = $conn->query("INSERT INTO Precios (pre_pro_emp, pre_fecha) 
                                                                 VALUES ('".$prodEmp->pem_id."', '".$Fecha."')");
                                
                                $consulta = "SELECT pre_id
                                             FROM Precios 
                                             Where pre_pro_emp = ".$prodEmp->pem_id." 
                                               and pre_fecha = '".$Fecha."'";
                                try {
                                    $resPrecios2 = $conn->query($consulta);
                                    $precio = $resPrecios2->fetch_object();
                                } catch (Exception $e) {
                                    echo 'Excepción capturada: ', $e->getMessage(), "\n";
                                }
                                
                                if ($precio->pre_id > 0) {
                                    $numCorte = 0;
                                    foreach ($cols as $col){
                                        if ($numCorte === 0){
                                            $numCorte ++;
                                        } elseif ($col->nodeValue !== "") {
                                            $conn->query("INSERT INTO Cortes (cor_idpre, cor_corte, cor_precio) 
                                                          VALUES ('".$precio->pre_id."', '".$numCorte."', '".$col->nodeValue."')");
                                            $numCorte ++;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $contador ++;
                } 
            }
        }
    }
?>
<html>
    <header>
            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- Banner 1 -->
            <ins class="adsbygoogle"
                 style="display:inline-block;width:320px;height:100px"
                 data-ad-client="ca-pub-5306727526505710"
                 data-ad-slot="8821370329"></ins>
            <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
    </header>
    <body>
    </body>
</html>