<?php
    header("Content-Type: text/html");

    include_once '../../includes/configBD.php';
    include_once '../../includes/authenticated.php';

    $auth = new Authenticate();
    $auth->auth($conn);

    function obtenerFecha($conn, $idEmp){
        try {
            $auth = new Authenticate();
            $numRows = $auth->auth($conn);
            if ($numRows == 1){
                $consulta = "SELECT max(pre_fecha) fecha
                             FROM Precios
                             Join Productos_Empresas on pem_id = pre_pro_emp
                             Where pem_idemp = " . $idEmp;

                $resPrecios = $conn->query($consulta);
                $fecha = $resPrecios->fetch_object();

                print($fecha->fecha);
            }
        } catch (Exception $e) {
            print($e->getMessage());
        }
    }
?>

<html xml:lang="es-ES" lang="es-ES">
<head>
    <meta http-equiv="refresh" content="60">

    <title>Carga de precios automaticos</title>

    <style>
        table, th, td {
          border: 1px solid black;
          border-collapse: collapse;
        }

        th, td {
            padding: 15px;
            text-align: center;
        }

        button {
            text-align: center;
            width: 200px;
        }
    </style>

</head>
<body>
  <center>
    <table>
        <tr>
            <th></th>
            <th>Ultima fecha de carga</th>
            <th>Hora aprox de carga</th>
        </tr>
        <tr>
            <td><button onclick="location.href='http://api.almeriacultiva.com/lectura/porEmpresa/leerAgrupaAdra.php'">AgrupaAdra</button></td>
            <td><?php obtenerFecha($conn, 1) ?></td>
            <td>10:30</td>
        </tr>
        <tr>
            <td><button onclick="location.href='http://api.almeriacultiva.com/lectura/porEmpresa/leerAgroejido.php'">Agroejido</button></td>
            <td><?php obtenerFecha($conn, 3) ?></td>
            <td>11:30</td>
        </tr>
        <tr>
            <td><button onclick="location.href='http://api.almeriacultiva.com/lectura/porEmpresa/leerCostaAlmeriaCehorpa.php'">Costa Almeria - Cehorpa</button></td>
            <td><?php obtenerFecha($conn, 2) ?></td>
            <td>12:00</td>
        </tr>
        <tr>
            <td><button onclick="location.href='http://api.almeriacultiva.com/lectura/porEmpresa/leerAgrupaejidoLaRedonda.php'">Agrupaejido - La Redonda</button></td>
            <td><?php obtenerFecha($conn, 4) ?></td>
            <td>12:30</td>
        </tr>
        <tr>
            <td><button onclick="location.href='http://api.almeriacultiva.com/lectura/porEmpresa/leerLaUnionLaRedonda.php'">La Union - La Redonda</button></td>
            <td><?php obtenerFecha($conn, 5) ?></td>
            <td>13:30</td>
        </tr>
        <tr>
            <td><button onclick="location.href='http://api.almeriacultiva.com/lectura/porEmpresa/leerAgroponiente.php'">Agroponiente</button></td>
            <td><?php obtenerFecha($conn, 6) ?></td>
            <td>13:30</td>
        </tr>
        <tr>
            <td><button onclick="location.href='http://api.almeriacultiva.com/lectura/porEmpresa/leerCostaAlmeriaRoquetas.php'">Costa Almeria - Roquetas</button></td>
            <td><?php obtenerFecha($conn, 9) ?></td>
            <td>14:00</td>
        </tr>
        <tr>
            <td><button onclick="location.href='http://api.almeriacultiva.com/lectura/porEmpresa/leerAgrupaejidoLaCosta.php'">Agrupaejido - La Costa</button></td>
            <td><?php obtenerFecha($conn, 7) ?></td>
            <td>17:00</td>
        </tr>
        <tr>
            <td><button onclick="location.href='http://api.almeriacultiva.com/lectura/porEmpresa/leerLaUnion4Vientos.php'">La Union - 4 Vientos</button></td>
            <td><?php obtenerFecha($conn, 8) ?></td>
            <td>19:00</td>
        </tr>
    </table>
  </center>
</body>
</html>