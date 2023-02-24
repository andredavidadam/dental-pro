<!doctype html>
<html lang="es">

<?php
include('inc/session.php');
include_once('inc/head.php');
include_once('inc/scripts.php');
include_once('inc/utility.php');

permiso(
    array(Tipologia::Rainweb => Rol::Administrador),
    'index.php'
);

$_SESSION['token'] = $token_session = getToken(32);
?>
<title>Rainweb - perfil</title>

<body>
    <?php
    // $sql = "SELECT username, nombre, apellido, email, telefono, tipologia, rol FROM usuario WHERE id = $id_usuario_session;";
    // $loop = mysqli_query($dbDentalPro, $sql);
    // while ($row = mysqli_fetch_assoc($loop)) {
    //     $nombre = $row["nombre"];
    //     $apellido = $row['apellido'];
    //     $username = $row['username'];
    //     $email = $row['email'];
    //     $telefono = $row['telefono'];
    //     $tipologia = $row['tipologia'];
    //     $rol = $row['rol'];
    // }
    ?>
    <!-- Modal actualizacion de datos -->
    <div class="modal fade" id="modal-update-usuario" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class=" modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Actualiza tus datos</h5>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" value=<?php echo $token_session ?> id="token">
                        <div class="row mb-2">
                            <div class="col">
                                <label for="modal-nombre" class="col-form-label">Nombre:</label>
                                <input type="text" class="form-control control-input" id="modal-nombre">
                                <small class="form-text">Escribe tu nombre</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <label for="modal-apellido" class="col-form-label">Apellido:</label>
                                <input type="text" class="form-control control-input" id="modal-apellido">
                                <small class="form-text">Escribe tu apellido</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <label for="modal-email" class="col-form-label">Email:</label>
                                <input type="text" class="form-control control-input" id="modal-email">
                                <small class="form-text">Escribe tu correo</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6 ">
                                <label for="modal-username" class="col-form-label">Nombre de usuario:</label>
                                <input type="text" class="form-control control-input" id="modal-username">
                                <small class="form-text">Escribe tu nombre de usuario</small>
                            </div>
                            <div class="col-6 ">
                                <label for="modal-telefono" class="col-form-label">Telefono:</label>
                                <input type="text" class="form-control control-input" id="modal-telefono">
                                <small class="form-text">Escribe tu telefono</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6 ">
                                <label for="modal-select-tipologia" class="col-form-label">Tipologia</label>
                                <select id="modal-select-tipologia" class="form-select">
                                    <option value="">Ninguna Tipologia</option>
                                    <?php
                                    foreach (Tipologia::getTipologias() as $key => $value) {
                                        echo "<option value='$value'>" . $key . "</option>";
                                    }
                                    ?>
                                </select>
                                <small class="form-text">Selecciona una tipologia</small>
                            </div>
                            <div class="col-6 ">
                                <label for="modal-select-rol" class="col-form-label">Rol:</label>
                                <select id="modal-select-rol" class="form-select">
                                    <option value="">Ningun Rol</option>
                                    <?php
                                    foreach (Rol::getRoles() as $key => $value) {
                                        echo "<option value='$value'>" . $key . "</option>";
                                    }
                                    ?>
                                </select>
                                <small class="form-text">Selecciona un rol</small>
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
    <!-- fin modal actualizar datos -->

    <?php include_once('componentes/componente-navbar-menu.php'); ?>
    <div class="container">
        <section class="hero-2 position-relative">
            <div class="row justify-content-center">
                <div class="col-6">
                    <div class="text-center title mb-5 mt-5">
                        <p class="text-muted text-uppercase fw-normal mb-2">Rainweb</p>
                        <h3 class="mb-3">Gestion Usuarios</h3>
                    </div>
                </div>
            </div>
        </section>
        <table id="tab-gestione-utenti" class="table table-striped table-bordered row-border dt-responsive nowrap mb-2" width="100%">
            <thead>
                <tr>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">Apellido</th>
                    <th class="text-center">Username</th>
                    <th class="text-center">Email</th>
                    <th class="text-center"'>Tipologia</th>
                    <th class="text-center"'>Rol</th>
                    <th class="text-center">Ultimo Acceso</th>
                    <th class="text-center"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT id, nombre, apellido, username, email, tipologia, rol, data_ultimo_acceso FROM usuario;";
                $loop = mysqli_query($dbDentalPro, $sql);
                while ($row = mysqli_fetch_assoc($loop)) {
                    $id = $row['id'];
                    $nombre = $row['nombre'] ?? '';
                    $apellido = $row['apellido'] ?? '';
                    $username = $row['username'] ?? '';
                    $email = $row['email'] ?? '';
                    $tipologia = $row['tipologia'] ?? '';
                    $rol = $row['rol'] ?? '';
                    $dataUltimoAcceso = $row['data_ultimo_acceso'] ?? '';
                ?>
                    <tr>
                        <td class='text-center align-middle'> <?php echo ucwords($nombre); ?></td>
                        <td class='text-center align-middle'> <?php echo ucwords($apellido); ?></td>
                        <td class='text-center align-middle'> <?php echo $username; ?></td>
                        <td class='text-center align-middle'> <?php echo $email; ?></td>
                        <?php
                        switch ($tipologia) {
                            case Tipologia::Rainweb:
                                echo "<td class='text-center align-middle text-primary fw-bold'>" . ucfirst($tipologia) . "</td>";
                                break;
                            case Tipologia::DentalPro:
                                echo "<td class='text-center align-middle fw-bold'>" . ucfirst($tipologia) . "</td>";
                                break;
                            default:
                                echo "<td class='text-center align-middle'> - </td>";
                                break;
                        }
                        ?>
                        <td class='text-center align-middle'> <?php echo ucfirst($rol); ?></td>
                        <td class='text-center align-middle'> <?php echo $dataUltimoAcceso; ?></td>

                        <td class="text-center align-middle">
                            <div>
                                <button class='btn btn-outline-primary update-usuario' value='<?php echo $id; ?>'><i class="bi bi-pencil-fill"></i>
                                </button>
                                <button class='btn btn-outline-danger delete-usuario' value='<?php echo $id; ?>'><i class='bi bi-trash-fill'></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
<script>
    $(document).ready(function() {
        // modal update datos
        const inputToken = $("#token");
        const inputNombre = $("#modal-nombre");
        const inputApellido = $("#modal-apellido");
        const inputEmail = $("#modal-email");
        const inputUsername = $("#modal-username");
        const inputTelefono = $("#modal-telefono");
        const selectTipologia = $("#modal-select-tipologia");
        const selectRol = $("#modal-select-rol");
        const pulsanteGuardarDatos = $("#pulsante-guardar-datos");

        const modalUpdateDatos = $("#modal-update-usuario");

        const pulsanteUpdateUsuario = $('.update-usuario');

        pressKey($(".control-input"));

        // muestra el modal para el usuario especifico
        pulsanteUpdateUsuario.on("click", function() {
            const usuario = $(this).val();

            if (usuario < 1) {
                message('error', 'El usuario no es valido');
            }

            $.ajax({
                url: "control/control-gestion-usuarios.php",
                type: "POST",
                data: {
                    "operacion": "modalGetDatos",
                    "usuario": usuario
                },
            }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
                message('error', 'No es posible conectarse al servidor <br> Intentalo mas tarde');
                return;
            }).done(function(response) {
                try {
                    var json = JSON.parse(response);
                } catch (e) {
                    message('error', 'Hubo un error al procesar los datos');
                    return;
                }

                if (!validateResponse(json)) {
                    return;
                }

                inputNombre.val(json['datos']['nombre']);
                inputApellido.val(json['datos']['apellido']);
                inputEmail.val(json['datos']['email']);
                inputUsername.val(json['datos']['username']);
                inputTelefono.val(json['datos']['telefono']);
                selectTipologia.val(json['datos']['tipologia']);
                selectRol.val(json['datos']['rol']);

                modalUpdateDatos.modal("show");
            });






            //modalUpdateDatos.modal('show');
        });

        pulsanteGuardarDatos.on("click", function() {
            const token = inputToken.val();
            const nombre = inputNombre.val();
            const apellido = inputApellido.val();
            const email = inputEmail.val();
            const username = inputUsername.val();
            const telefono = inputTelefono.val();
            const tipologia = selectTipologia.val();
            const rol = selectRol.val();

            if (nombre.length == 0) {
                invalidInput([inputNombre], 'Introduce un nombre');
                return;
            } else if (nombre.length < 3 || nombre.length > 40) {
                invalidInput([inputNombre], 'El nombre debe tener mas de 3 letras y menos de 40');
                return;
            }

            if (apellido.length == 0) {
                invalidInput([inputApellido], 'Introduce un apellido');
                return;
            } else if (apellido.length < 3 || apellido.length > 40) {
                invalidInput([inputApellido], 'El apellido debe tener mas de 3 caracteres y menos de 40');
                return;
            }

            if (email.length == 0) {
                invalidInput([inputEmail], 'Introduce un email');
                return;
            } else if (!validateEmail(email)) {
                invalidInput([inputEmail], 'Introduce un email valido');
                return;
            }

            if (username.length == 0) {
                invalidInput([inputUsername], 'Introduce un nombre de usuario');
                return;
            } else if (username.length < 5 || username.length > 20) {
                invalidInput([inputUsername], 'El nombre de usuario debe tener mas de 5 caracteres y menos de 20');
                return;
            }

            if (telefono.length < 7 && telefono.length != 0) {
                invalidInput([inputTelefono], 'Introduce un numero valido o deja el campo vacio');
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
                    "tipologia":tipologia,
                    "rol":rol
                },
            }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
                message('error', 'No es posible conectarse al servidor <br> Intentalo mas tarde');
                return;
            }).done(function(response) {
                try {
                    var json = JSON.parse(response);
                } catch (e) {
                    message('error', 'Hubo un error al procesar los datos');
                    return;
                }

                if (!validateResponse(json)) {
                    return;
                }

                $.confirm({
                    icon: 'bi bi-check-circle-fill',
                    title: 'Bien hecho!',
                    content: 'Los datos se actualizaron correctamente',
                    type: 'green',
                    typeAnimated: true,
                    buttons: {
                        ok: {
                            text: 'Ok',
                            btnClass: 'btn-green',
                            action: function() {
                                modalUpdateDatos.modal('hide');
                            }
                        }
                    }
                });
            });
        });


    });
</script>

</html>