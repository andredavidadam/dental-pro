<!doctype html>
<html lang="en">

<?php
include_once('inc/session.php');
include_once('inc/head.php');
include_once('inc/scripts.php');
include_once('inc/utility.php');
if (!isLogado()) {
    exit;
}
?>
<title>Rainweb - perfil</title>

<body>
    <?php
    $sql = "SELECT username, nombre, apellido, email, telefono, tipologia, rol FROM usuario WHERE id = $id_usuario_session;";
    $loop = mysqli_query($dbDentalPro, $sql);
    while ($riga = mysqli_fetch_assoc($loop)) {
        $nombre = $riga['nombre'];
        $apellido = $riga['apellido'];
        $username = $riga['username'];
        $email = $riga['email'];
        $telefono = $riga['telefono'];
        $tipologia = $riga['tipologia'];
        $rol = $riga['rol'];
    }
    $nombreCompleto = ucfirst($nombre) . ' ' . ucfirst($apellido);
    $nivelAcceso = ucfirst($rol) . ' ' . ucfirst($tipologia);
    ?>
    <!-- Modal -->
    <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class=" modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Actualiza tus datos</h5>
                </div>
                <div class="modal-body">
                    <form id="form-actualiza-datos">
                        <input type="hidden" value="actualizaDatos" name="operacion" id="operacion">
                        <input type="hidden" value=<?php $_SESSION['token'] = getToken(16) ?> name="token" id="token">
                        <div class="row mb-2">
                            <div class="col">
                                <label for="modal-nombre" class="col-form-label">Nombre:</label>
                                <input type="text" class="form-control" value=<?php echo ucfirst($nombre) ?> name="modalNombre" id="modal-nombre" required>
                                <small class="form-text">Escribe tu nombre</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <label for="modal-apellido" class="col-form-label">Apellido:</label>
                                <input type="text" class="form-control" value=<?php echo ucfirst($apellido) ?> name="modalApellido" id="modal-apellido" required>
                                <small class="form-text">Escribe tu apellido</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <label for="modal-email" class="col-form-label">Email:</label>
                                <input type="text" class="form-control" value=<?php echo $email ?> name="modalEmail" id="modal-email">
                                <small class="form-text">Escribe tu correo</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6 ">
                                <label for="modal-username" class="col-form-label">Nombre de usuario:</label>
                                <input type="text" class="form-control" value=<?php echo $username ?> name="modalUsername" id="modal-username">
                                <small class="form-text">Escribe tu nombre de usuario</small>
                            </div>
                            <div class="col-6 ">
                                <label for="modal-telefono" class="col-form-label">Telefono:</label>
                                <input type="text" class="form-control" value=<?php echo $telefono ?> name="modalTelefono" id="modal-telefono">
                                <small class="form-text">Escribe tu telefono</small>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="pulsante-guardar-datos">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- fine modal -->
    <?php include_once('componentes/componente-navbar-menu.php'); ?>
    <div class="container">
        <section class="hero-2 position-relative">
            <div class="row justify-content-center">
                <div class="col-6">
                    <div class="text-center title mb-5 mt-5">
                        <p class="text-muted text-uppercase fw-normal mb-2">Rainweb</p>
                        <h3 class="mb-3">Visualiza Perfil</h3>
                    </div>
                </div>
            </div>
        </section>
        <div class="row">
            <div class="col-6">
                aqui la foto
            </div>
            <div class="col-6">
                <?php
                $sql = "SELECT username, nombre, apellido, email, telefono, tipologia, rol FROM usuario WHERE id = $id_usuario_session;";
                $loop = mysqli_query($dbDentalPro, $sql);
                while ($row = mysqli_fetch_assoc($loop)) {
                    $username = $row['username'];
                    $nombreCompleto = ucfirst($row['nombre']) . ' ' . ucfirst($row['apellido']);
                    $email = $row['email'];
                    $telefono = $row['telefono'];
                    $nivelAcceso = ucfirst($row['rol']) . ' ' . ucfirst($row['tipologia']);
                }
                ?>
                <h3 class="text-primary"><?php echo $nombreCompleto ?></h3>
                <p class="text-muted fw-normal mb-2"><?php echo $nivelAcceso ?></p>
                <hr>
                <label class="form-label" for="username">Nombre de Usuario</label>
                <p id="username"><b><?php echo $username ?></b></p>
                <label class="form-label" for="email">email</label>
                <p id="email"><b><?php echo $email ?></b></p>
                <label class="form-label" for="email">telefono</label>
                <p id="email"><b><?php echo $telefono ?></b></p>
                <div class="row">
                    <div class="col">
                        <button class="btn btn-info mt-3 ms-3 me-3" id="pulsante-cambiar-datos">Modificar Datos</button>
                        <button class="btn btn-info mt-3 ms-3 me-3" id="pulsante-cambiar-password">Cambia Password</button>
                    </div>
                </div>
            </div>
        </div>
</body>
<script>
    $(document).ready(function() {
        const formActualizaDatos = $("#form-actualiza-datos");

        const pulsanteCambiarDatos = $("#pulsante-cambiar-datos");
        const pulsanteGuardarDatos = $("#pulsante-guardar-datos");

        const modal = $('#modal');

        pulsanteCambiarDatos.on("click", function(e) {
            e.preventDefault();
            modal.modal('show');
        });

        pulsanteGuardarDatos.on("click", function(e) {
            e.preventDefault();
            guardarDatos();
            modal.modal('hide');


        });

        function guardarDatos() {
            $.ajax({
                url: "control/control-gestion-usuarios.php",
                type: "POST",
                data: formActualizaDatos.serialize(),
            }).done(function(response) {
                console.log(response);
                return;
            }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
                // FAIL DA MODIFICARE
                alert('token invalido');
            });
        }
    });
</script>

</html>