<!doctype html>
<html lang="en">

<?php
include('inc/session.php');
include_once('inc/head.php');
include_once('inc/scripts.php');
include_once('inc/utility.php');

$_SESSION['token'] = getToken(16);

// esta pagina la pueden ver todos los usuarios que iniciaron sesion
// si no se inicia sesion lo mando al index

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
    <!-- Modal actualizacion de datos -->
    <div class="modal fade" id="modal-actualizar-datos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class=" modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Actualiza tus datos</h5>
                </div>
                <div class="modal-body">
                    <form id="form-actualiza-datos">
                        <input type="hidden" value=<?php echo $_SESSION['token'] ?> id="token">
                        <div class="row mb-2">
                            <div class="col">
                                <label for="modal-nombre" class="col-form-label">Nombre:</label>
                                <input type="text" class="form-control control-input" value=<?php echo ucfirst($nombre) ?> id="modal-nombre" required>
                                <small class="form-text">Escribe tu nombre</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <label for="modal-apellido" class="col-form-label">Apellido:</label>
                                <input type="text" class="form-control control-input" value=<?php echo ucfirst($apellido) ?> id="modal-apellido" required>
                                <small class="form-text">Escribe tu apellido</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <label for="modal-email" class="col-form-label">Email:</label>
                                <input type="text" class="form-control control-input" value=<?php echo $email ?> id="modal-email">
                                <small class="form-text">Escribe tu correo</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6 ">
                                <label for="modal-username" class="col-form-label">Nombre de usuario:</label>
                                <input type="text" class="form-control control-input" value=<?php echo $username ?> id="modal-username">
                                <small class="form-text">Escribe tu nombre de usuario</small>
                            </div>
                            <div class="col-6 ">
                                <label for="modal-telefono" class="col-form-label">Telefono:</label>
                                <input type="text" class="form-control control-input" value=<?php echo $telefono ?> id="modal-telefono">
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
                        <button type="button" class="btn btn-info mt-3 ms-3 me-3" id="pulsante-cambiar-datos">Modificar Datos</button>
                        <button type="button" class="btn btn-info mt-3 ms-3 me-3" id="pulsante-cambiar-password">Cambia Password</button>
                    </div>
                </div>
            </div>
        </div>
</body>
<script>
    $(document).ready(function() {
        const inputToken = $("#token");
        const inputNombre = $("#modal-nombre");
        const inputApellido = $("#modal-apellido");
        const inputEmail = $("#modal-email");
        const inputUsername = $("#modal-username");
        const inputTelefono = $("#modal-telefono");
        const controlInput = $(".control-input");

        const pulsanteCambiarDatos = $("#pulsante-cambiar-datos");
        const pulsanteGuardarDatos = $("#pulsante-guardar-datos");

        const modalActualizarDatos = $('#modal-actualizar-datos');

        pressKey(controlInput);

        pulsanteCambiarDatos.on("click", function(e) {
            modalActualizarDatos.modal('show');
        });

        pulsanteGuardarDatos.on("click", function() {
            const token = inputToken.val();
            const nombre = inputNombre.val();
            const apellido = inputApellido.val();
            const email = inputEmail.val();
            const username = inputUsername.val();
            const telefono = inputTelefono.val();

            if (nombre.length == 0) {
                invalidInput(inputNombre, 'Introduce un nombre');
                return;
            } else if (nombre.length < 3 || nombre.length > 20) {
                invalidInput(inputNombre, 'El nombre debe tener mas de 3 letras y menos de 20');
                return;
            }

            if (apellido.length == 0) {
                invalidInput(inputApellido, 'Introduce un apellido');
                return;
            } else if (apellido.length < 3 || apellido.length > 20) {
                invalidInput(inputApellido, 'El apellido debe tener mas de 3 caracteres y menos de 20');
                return;
            }

            if (email.length == 0) {
                invalidInput(inputEmail, 'Introduce un email');
                return;
            } else if (!validateEmail(email)) {
                invalidInput(inputEmail, 'Introduce un email valido');
                return;
            }

            if (username.length == 0) {
                invalidInput(inputUsername, 'Introduce un nombre de usuario');
                return;
            } else if (username.length < 5 || username.length > 20) {
                invalidInput(inputUsername, 'El nombre de usuario debe tener mas de 5 caracteres y menos de 20');
                return;
            }

            if (telefono.length < 7 && telefono.length != 0) {
                invalidInput(inputTelefono, 'Introduce un numero valido o deja el campo vacio');
                return;
            }

            $.ajax({
                url: "control/control-gestion-usuarios.php",
                type: "POST",
                data: {
                    "operacion": "actualizarDatos",
                    "token": token,
                    "nombre": nombre,
                    "apellido": apellido,
                    "email": email,
                    "username": username,
                    "telefono": telefono,
                },
            }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
                message('error', 'No es posible conectarse al servidor <br> Intentalo mas tarde');
                return;
            }).done(function(response) {
                console.log(response);
                return;
                try {
                    var json = JSON.parse(response);
                } catch (e) {
                    message('error', 'Hubo un error al procesar los datos');
                    return;
                }

                if (!validateResponse(json)) {
                    return;
                }
                modalActualizarDatos.modal('hide');
            });

        });
    });
</script>

</html>