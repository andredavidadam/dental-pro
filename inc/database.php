<?php

/* DATABASE DentalPro */
$dbhostDentalPro = 'localhost';
$dbuserDentalPro = 'root';
$dbpassDentalPro = 'root';
$dbnameDentalPro = 'dentalpro';

$dbDentalPro = mysqli_connect($dbhostDentalPro, $dbuserDentalPro, $dbpassDentalPro, $dbnameDentalPro);

if (!$dbDentalPro) {
    echo 'no puedo conectarme a la base de datos';
}
