<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: X-Requested-With, Origin, Content-Type, X-Auth-Token, Authorization, Accept");
    header("Content-Type: text/html");

    include_once 'funcionesLectura.php';

    $url = 'http://www.agropizarra.com/es/pizarra-subasta/agroejido-el-ejido';
    $idEmpresa = 3;
    $empresa = 'Agroejido';

    leerAlhondiga($url, $idEmpresa, $empresa);
  ?>