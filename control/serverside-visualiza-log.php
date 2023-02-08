<?php
include_once("../inc/session.php");
include_once('../inc/database.php');
include_once('../inc/utility.php');

require('ssp.class.php');

$operacion = $_POST['operacion'] ?? '';

switch ($operacion) {
    case 'getTablaLog':

        $tablaMysql = 'log';
        $primaryKey = 'id';

        $tabla = array(
            array(
                'db' => 'created_by',
                'dt' => 0,
                'formatter' => function ($elemento, $row) {
                    if ($elemento == "sistema")
                        return "<span class='text-muted'>" . $elemento . "<span>";
                    else
                        return "<span class='fw-bold'>" . $elemento . "<span>";
                }
            ),
            array(
                'db' => 'operacion',
                'dt' => 1
            ),
            array(
                'db' => 'descripcion',
                'dt' => 2,
                'formatter' => function ($elemento, $row) {
                    return nl2br($elemento);
                }
            ),
            array(
                'db' => 'timestamp',
                'dt' => 3
            ),
        );

        $sql_details = array(
            'user' => $dbuserDentalPro,
            'pass' => $dbpassDentalPro,
            'db'   => $dbnameDentalPro,
            'host' => $dbhostDentalPro,
        );

        echo json_encode(
            SSP::simple($_POST, $sql_details, $tablaMysql, $primaryKey, $tabla)
        );

        return;
        default:
        //$mensaje = 'operacion no encontrada';
        error('error');
}
