<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: X-Requested-With, Origin, Content-Type, X-Auth-Token, Authorization, Accept");
    header("Content-Type: text/html");

    require("../simple_html_dom.php");

    include_once '../../includes/configBD.php';
    include_once '../../includes/authenticated.php';
    include_once 'funcionesLectura.php';

    $url = 'http://www.agropizarra.com/es/pizarra-subasta/agroejido-el-ejido';
    $idEmpresa = 3;
    $empresa = 'Agroejido';

    $mensaje = '';
    $sitioweb = curl($url, $mensaje);

    $pos = strpos($sitioweb, 'Forbidden');
    if ($pos != false) {
      echo $sitioweb . '<br />';
      exit();
    }

    if ($mensaje)
      echo $mensaje . '<br />';

    $auth = new Authenticate();

    $numRows = $auth->auth($conn);
    if ($numRows == 1){

        $dom = new domDocument;
        $dom->loadHTML($sitioweb);
        $dom->preserveWhiteSpace = false;

        $fechaPagina = buscarFecha($dom, "fec");

        echo $fechaPagina . ' - ' . date("d-m-Y") . '<br/>';
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

                    //echo $consulta . '<br/>';
                    try {
                        $resProdEmp = $conn->query($consulta);
                        $prodEmp = $resProdEmp->fetch_object();
                     } catch (Exception $e) {
                        echo 'error<br/>';
                        echo 'Excepción capturada: ', $e->getMessage(), "<br/>";
                     }

                    echo '$prodEmp->pem_id -> ' . $prodEmp->pem_id . '<br/>';

                    $consulta = "SELECT pre_id
                                 FROM Precios
                                 Where pre_pro_emp = " . $prodEmp->pem_id . "
                                   and pre_fecha = '" . date("Y-m-d") . "'";

                    $resPrecios = $conn->query($consulta);

                    $totalFilas = $resPrecios->num_rows;
                    echo 'totalFilas -> ' . $totalFilas . '<br/>';
                    if ($totalFilas === 0) {
                        $cargaOK = true;
                        echo $etiqueta . ' -> ';
                        $PrecioInsertado = $conn->query("INSERT INTO Precios (pre_pro_emp, pre_fecha)
                                                         VALUES ('".$prodEmp->pem_id."', '". date("Y-m-d") ."')");

                        try {
                            $resPrecios2 = $conn->query("SELECT pre_id
                                                         FROM Precios
                                                         Where pre_pro_emp = ".$prodEmp->pem_id."
                                                           and pre_fecha = '" . date("Y-m-d") . "'");
                            $precio = $resPrecios2->fetch_object();
                         } catch (Exception $e) {
                            echo 'Excepción capturada: ', $e->getMessage(), "<br/>";
                          }

                         if ($precio->pre_id > 0) {
                           $numCorte = 0;
                           foreach ($cols as $col){
                             if ($numCorte === 0){
                                 $numCorte ++;
                              } elseif ($col->nodeValue !== "") {
                                 echo $col->nodeValue . ' - ';
                                 $conn->query("INSERT INTO Cortes (cor_idpre, cor_corte, cor_precio)
                                               VALUES ('".$precio->pre_id."', '".$numCorte."', '".$col->nodeValue."')");
                                 $numCorte ++;
                              }
                            }
                            echo '<br/>';
                         }


                        $conn->query("UPDATE Precios
                                      SET pre_media3 = (Select avg(cor_precio)
                                                        From Cortes
                                                        Where cor_corte <= 3
                                                        and cor_idpre = pre_id )
                                      Where pre_media3 is null");
                        echo '<br />';
                     }
                 }
             }

            if ($cargaOK){
                $resp = enviarMail($url, $empresa, 'Los precios de '. $empresa .' se han cargado correctamente.');
                echo $resp;
             } else {
                $resp = enviarMail($url, $empresa, 'Los precios de '. $empresa .' ya estaban cargados.');
                echo $resp;
             }

         } else {
            $resp = enviarMail($url, $empresa, 'Todavia no hay precios para '. $empresa);
            echo $resp;
         }
     }
  ?>