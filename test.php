<?php
$test = getGUID();
var_dump($test);

function getGUID()
{
    echo 'ciao';
    //--- Generar GUID de 32 bytes.
    usleep(1309);
    return strtolower(md5(uniqid()));
}
