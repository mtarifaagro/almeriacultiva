<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: X-Requested-With, Origin, Content-Type, X-Auth-Token, Authorization, Accept");
    header("Content-Type: text/html");

    include_once 'funcionesLectura.php';

    $url = 'http://www.agroprecios.com/es/precios-subasta/8-costa-almeria-roquetas';
    $idEmpresa = 9;
    $empresa = 'Costa Almería - Roquetas';

    leerAlhondiga2($url, $idEmpresa, $empresa);
?>