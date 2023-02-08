<!doctype html>
<html lang="en">

<?php
include_once('inc/session.php');
include_once('inc/head.php');
include_once('inc/scripts.php');
include_once('inc/utility.php');

permiso(array('rainweb' => 'administrador'));
?>
<title>Rainweb - log</title>

<body>
    <?php include_once('componentes/componente-navbar-menu.php'); ?>

    <!-- Hero section Start -->
    <div class="container">
        <section class="hero-2 position-relative">
            <div class="row justify-content-center">
                <div class="col-6">
                    <div class="text-center title mb-5 mt-5">
                        <p class="text-muted text-uppercase fw-normal mb-2">Administracion</p>
                        <h3 class="mb-3">Visualiza Log</h3>
                    </div>
                </div>
            </div>
        </section>
        <!-- end container -->
        <!-- end row -->
        <div class="row hero-2-content">
            <table id="tab-log">
                <thead>
                    <tr>
                        <th class="text-center">Usuario</th>
                        <th class="text-center">Operacion</th>
                        <th class="text-center">Descripcion</th>
                        <th class="text-center">Fecha</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <!-- end row -->
    <!-- Hero section End -->
    <script>
        $(document).ready(function() {
            /* tabella dinamica */
            let tablaLog = $('#tab-log').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: "control/serverside-visualiza-log.php",
                    type: "POST",
                    data: {
                        "operacion": "getTablaLog"
                    },
                },
                responsive: true,
                columnDefs: [{
                    orderable: false,
                    "width": "15%",
                    "className": "text-center align-middle white-space",
                    targets: 0
                }, {
                    orderable: false,
                    "width": "15%",
                    "className": "text-center align-middle white-space",
                    targets: 1
                }, {
                    orderable: false,
                    "className": "text-center align-middle",
                    targets: 2
                }, {
                    orderable: true,
                    "className": "text-center align-middle",
                    targets: 3
                }],
                "order": [
                    [3, "desc"]
                ],
                "bPaginate": true,
                "lengthChange": false,
                "oLanguage": {
                    "sInfo": "Estas viendo <b>de _START_ a _END_</b> de _TOTAL_ elementos",
                    "sInfoEmpty": "Visualizzati 0 per 0 di 0 elementi",
                    "sInfoEmpty": "No hay nada para mostrar",
                    "sInfoFiltered": "(filtrado sobre un total de&nbsp;_MAX_&nbsp;elementos)",
                    "sSearch": "",
                    "sSearchPlaceholder": "Buscar...",
                    "sZeroRecords": "Ningun elemento encontrado",
                    "oPaginate": {
                        "sFirst": "Primera pagina",
                        "sLast": "Ultima pagina",
                        "sNext": "siguiente",
                        "sPrevious": "Atras"
                    }
                },
                "iDisplayLength": 50,
                colReorder: true
            });
        });
    </script>
</body>

</html>