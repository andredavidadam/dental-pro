<!doctype html>
<html lang="en">

<?php
include_once('inc/session.php');
include_once("inc/head.php");
include_once("inc/scripts.php");
include_once("inc/utility.php");

// solo los administradores de rainweb pueden registrar a usuarios
// los demas son redireccionados
if ($is_logado_session === true && $tipologia_session !== Tipologia::Rainweb) {
    header("Location: index.php");
    return;
}
?>

<body>
    <div class="container">
        <section class="hero-2 position-relative">
            <div class="row justify-content-center">
                <div class="col-6">
                    <div class="text-center title mb-5 mt-5">
                        <p class="text-muted text-uppercase fw-normal mb-2">Administracion</p>
                        <h3 class="mb-3">Visualiza Perfil</h3>
                    </div>
                </div>
            </div>
        </section>
        <div class="row">
            <div class="col text-center">
                <h1>Pagina de registro</h1>
            </div>
        </div>
        <div class="row justify-content-center mt-3 mb-3">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <form id="form-registro">
                            <input type="hidden" value="registro" name="operacion" id="operacion">
                            <div class="row">
                                <div class="col-6">
                                    <label for="input-nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" name="inputNombre" id="input-nombre" minlength="3" maxlength="20" value="angelo" autofocus required>
                                    <small class="form-text">Escribe tu nombre</small>
                                </div>
                                <div class="col-6">
                                    <label for="input-apellido" class="form-label">Apellido</label>
                                    <input type="text" class="form-control" name="inputApellido" id="input-apellido" minlength="3" maxlength="20" value="lagroia" required>
                                    <small class="form-text">Escribe tu apellido</small>
                                </div>
                            </div>
                            <div class="row mt-3 mb-3">
                                <div class="col-12">
                                    <label for="input-email" class="form-label">Email</label>
                                    <input type="email" class="form-control" name="inputEmail" id="input-email" value="angelo@gmail.com" required>
                                    <small class="form-text">Escribe tu email</small>
                                </div>
                            </div>
                            <div class="row mt-3 mb-3">
                                <div class=" col-8">
                                    <label for="input-username" class="form-label">Nombre de Usuario</label>
                                    <input type="text" class="form-control" name="inputUsername" id="input-username" minlength="3" maxlength="20" value="angelo" required>
                                    <small class="form-text">Escribe tu nombre de usuario</small>
                                </div>
                                <div class="col-4">
                                    <label for="input-telefono" class="form-label">Telefono</label>
                                    <input type="text" pattern="\d*" class="form-control" name="inputTelefono" id="input-telefono" value="123765489" required>
                                    <small class="form-text">Escribe tu Telefono</small>
                                </div>
                            </div>
                            <div class="row mt-3 mb-3">
                                <div class="col-6">
                                    <label for="input-password" class="form-label">Password</label>
                                    <input type="text" class="form-control" name="inputPassword" id="input-password" minlength="10" maxlength="32" value="1234567890" required></input>
                                    <small class="form-text">Escribe tu contraseña</small>
                                </div>
                                <div class="col-6">
                                    <label for="input-confirm-password" class="form-label">Confirma Password</label>
                                    <input type="text" class="form-control" name="inputConfirmPassword" id="input-confirm-password" minlength="10" maxlength="32" value="1234567890" required></input>
                                    <small class="form-text">Confirma tu contraseña</small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mt-3 mb-3">
                                    <button type="submit" id='pulsante-registro' class="btn btn-primary float-end">Registrate</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            const inputNombre = $("#input-nombre");
            const inputApellido = $("#input-apellido");
            const inputEmail = $("#input-email");
            const inputTelefono = $("#input-telefono");
            const inputUsername = $("#input-username");
            const inputPassword = $("#input-password");
            const inputConfirmPassword = $("#input-confirm-password");

            const pulsanteRegistro = $("#pulsante-registro");

            const formRegistro = $("#form-registro");

            pulsanteRegistro.on("click", function(e) {
                password = inputPassword.val();
                confirmPassword = inputConfirmPassword.val();
                if (password !== confirmPassword) {
                    $.alert({
                        title: 'Alerta',
                        content: 'Las contraseñas no coinciden'
                    });
                    console.log('las contraseñas no coinciden');
                }
                return;
                //registro();
            });



            function registro() {
                $.ajax({
                    url: "control/control-registro.php",
                    type: 'POST',
                    data: {
                        "operacion": "registro",
                        "nombre": inputNombre.val(),
                        "apellido": inputApellido.val(),
                        "email": inputEmail.val(),
                        "username": inputUsername.val(),
                        "password": inputPassword.val(),
                        "confirmPassword": inputConfirmPassword.val()
                    },
                }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
                    alert('el registro no se pudo realizar');
                }).done(function(response) {
                    let json = JSON.parse(response);
                    if (json['status'] == 'success') {
                        alert(json['mensaje']);
                        window.location.href = "login.php";
                    } else {
                        alert(json['mensaje']);
                    }
                });
                return;
            }

        });
    </script>

</body>

</html>