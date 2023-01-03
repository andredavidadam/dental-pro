<?php
include("../inc/database.php");
include("../inc/session.php");
include("../inc/utility.php");

permisoControl();

$operacion = $_POST['operacion'] ?? '';

switch ($operacion) {
    case 'getTablaLog':
        $arrayResult = [];
        $sql = "SELECT id, operacion, descripcion, created_by, timestamp from log ORDER BY timestamp DESC LIMIT 10;";
        $loop = mysqli_query($dbDentalPro, $sql);
        while ($row = mysqli_fetch_assoc($loop)) {
            $id = $row['id'];
            $operacion = $row['operacion'];
            $descripcion = $row['descripcion'];
            $created_by = $row['created_by'];
            $timestamp = $row['timestamp'];

            $arrayResult[] = [
                // '0' => $id,
                '0' => $created_by,
                '1' => $operacion,
                '2' => $descripcion,
                '3' => $timestamp,
                "4" => "
                <button tabindex='0' role='button' data-bs-trigger='focus' class='btn btn-danger me-2' data-bs-placement='top' data-bs-toggle='popover' style='padding: 2px 10px;' title='Confermi la rimozione?' data-bs-content=\" <div class='row d-flex justify-content-center text-center'> <div>
                <button value='$id' class='btn btn-danger delete-colore'><i class='fa-solid fa-trash-can me-2'></i>Si</button> <button class='btn btn-warning chiudi-popover'>Annulla</button> </div> </div> \"><i class='fa-solid fa-trash-can'></i> </button>
                <button value='$id' class='btn btn-dark modifica-colore' style='padding: 2px 10px;'><i class='fa-solid fa-pen-to-square'></i>
                </button>"
            ];
        }
        echo json_encode($arrayResult);
        return;
    default:
        //$mensaje = 'operacion no encontrada';
        error('error');
}
