<!doctype html>
<html lang="en">

<?php
include('inc/session.php');
include('inc/head.php');
include('inc/scripts.php');
include('inc/utility.php');
permiso(array('rainweb' => 'administrador'));
?>
<title>Rainweb - Dashboard</title>

<body>
    <?php include('componentes/componente-navbar-perfil.php'); ?>

    <!-- Hero section Start -->
    <section class="hero-2 position-relative">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-6">
                    <div class="text-center title mb-5 mt-5">
                        <p class="text-muted text-uppercase fw-normal mb-2">Administracion</p>
                        <h3 class="mb-3">Visualiza Log</h3>
                    </div>
                </div>
            </div>
            <!-- end row -->
            <div class="row hero-2-content">
                <table id="tab-log">
                    <thead>
                        <tr>
                            <th class="text-center">Usuario</th>
                            <th class="text-center">Operacion</th>
                            <th class="text-center">Descripcion</th>
                            <th class="text-center">Fecha</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->

    </section>
    <!-- Hero section End -->
    <script>
        $(document).ready(function() {
            /* tabella dinamica */
            let tablaLog = $('#tab-log').DataTable({
                dom: 'lrtip',
                responsive: true,
                columnDefs: [{
                    orderable: true,
                    "className": "text-center align-middle white-space",
                    targets: 0
                }, {
                    orderable: false,
                    "className": "text-center align-middle white-space",
                    targets: 1
                }, {
                    orderable: false,
                    "className": "text-center align-middle",
                    targets: 2
                }, {
                    orderable: false,
                    "className": "text-center align-middle",
                    targets: 3
                }, {
                    orderable: false,
                    "className": "text-center align-middle",
                    targets: 3
                }],
                "order": [
                    [0, "asc"]
                ],
                "bPaginate": true,
                "lengthChange": false,
                "oLanguage": {
                    "sInfo": "Stai visualizzando <b>da _START_ a _END_</b> di _TOTAL_ elementi",
                    "sInfoEmpty": "Visualizzati 0 per 0 di 0 elementi",
                    "sInfoFiltered": "(filtrati su un totale di&nbsp;_MAX_&nbsp;elementi)",
                    "sSearch": "",
                    "sSearchPlaceholder": "Ricerca",
                    "sZeroRecords": "Nessun elemento trovato",
                    "oPaginate": {
                        "sFirst": "Prima pagina",
                        "sLast": "Ultima pagina",
                        "sNext": "Avanti",
                        "sPrevious": "Indietro"
                    }
                },
                "iDisplayLength": 25,
                colReorder: true,
                "drawCallback": function(settings) {
                    /* inizializzo tutti i popovers */
                    $("[data-bs-toggle='popover']").each(function(indice, elemento) {
                        return new bootstrap.Popover(elemento, {
                            html: true,
                            sanitize: false,
                            position: "fixed",
                            placement: "top",
                        })
                    });
                }
            });

            actualizaTabla();

            function actualizaTabla() {
                $.ajax({
                    url: "control/control-visualiza-log.php",
                    type: 'POST',
                    data: {
                        "operacion": "getTablaLog"
                    },
                }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
                console.log('error en el ajax');
                }).done(function(response) {
                    let json = JSON.parse(response);
                    tablaLog.clear();
                    tablaLog.rows.add(json);
                    tablaLog.draw();

                    $('#tab-log').off('click', '.modifica-colore');
                    $('#tab-log').off('shown.bs.popover', "[data-bs-toggle='popover']");

                    $('#tab-log').on('click', '.modifica-colore', function() {
                        ImpostaCampiModificaColore($(this).val());
                    });

                    $('#tab-log').on('shown.bs.popover', "[data-bs-toggle='popover']", function() {
                        $(".delete-colore").on('click', function() {
                            DeleteColore($(this).val());
                        });
                        $(".chiudi-popover").on('click', function() {
                            $("[data-bs-toggle='popover']").popover('hide');
                        });
                    });
                });
            }
        });
    </script>
</body>

</html>