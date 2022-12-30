
<?php

/* DATABASE DentalPro */
$dbhostDentalPro = 'localhost';
$dbuserDentalPro = 'root';
$dbpassDentalPro = '4765178';
$dbnameDentalPro = 'proyecto';

$dbDentalPro = mysqli_connect($dbhostDentalPro, $dbuserDentalPro, $dbpassDentalPro, $dbnameDentalPro);

if(!$dbDentalPro){
    echo 'no puedo conectarme a la base de datos';
}