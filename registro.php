<!doctype html>
<html lang="en">

<?php
include_once('inc/session.php');
include_once("inc/head.php");
include_once("inc/scripts.php");
include_once("inc/utility.php");

// solo los administradores de rainweb pueden registrar a usuarios
// los demas son redireccionados
if (isLogado()) {
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
                        <form>
                            <div class="row">
                                <div class="col-6">
                                    <label for="input-nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="input-nombre" minlength="3" maxlength="20" value="angelo" autofocus required>
                                    <small class="form-text">Escribe tu nombre</small>
                                </div>
                                <div class="col-6">
                                    <label for="input-apellido" class="form-label">Apellido</label>
                                    <input type="text" class="form-control" id="input-apellido" minlength="3" maxlength="20" value="lagroia" required>
                                    <small class="form-text">Escribe tu apellido</small>
                                </div>
                            </div>
                            <div class="row mt-3 mb-3">
                                <div class="col-12">
                                    <label for="input-email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="input-email" value="angelo@gmail.com" required>
                                    <small class="form-text">Escribe tu email</small>
                                </div>
                            </div>
                            <div class="row mt-3 mb-3">
                                <div class=" col-8">
                                    <label for="input-username" class="form-label">Nombre de Usuario</label>
                                    <input type="text" class="form-control" id="input-username" minlength="3" maxlength="20" value="angelo" required>
                                    <small class="form-text">Escribe tu nombre de usuario</small>
                                </div>
                                <div class="col-4">
                                    <label for="input-telefono" class="form-label">Telefono</label>
                                    <input type="text" pattern="\d*" class="form-control" id="input-telefono" value="123765489" required>
                                    <small class="form-text">Escribe tu Telefono</small>
                                </div>
                            </div>
                            <div class="row mt-3 mb-3">
                                <div class="col-6">
                                    <label for="input-password" class="form-label">Password</label>
                                    <input type="text" class="form-control" id="input-password" minlength="10" maxlength="32" value="1234567890aa" required></input>
                                    <small class="form-text">Escribe tu contraseña</small>
                                </div>
                                <div class="col-6">
                                    <label for="input-confirm-password" class="form-label">Confirma Password</label>
                                    <input type="text" class="form-control" id="input-confirm-password" minlength="10" maxlength="32" value="1234567890aa" required></input>
                                    <small class="form-text">Confirma tu contraseña</small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mt-3 mb-3">
                                    <button type="button" id='pulsante-registro' class="btn btn-primary float-end">Registrate</button>
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
            const inputUsername = $("#input-username");
            const inputTelefono = $("#input-telefono");
            const inputPassword = $("#input-password");
            const inputConfirmPassword = $("#input-confirm-password");

            const pulsanteRegistro = $("#pulsante-registro");

            pulsanteRegistro.on("click", function(e) {
                const nombre = inputNombre.val();
                const apellido = inputApellido.val();
                const email = inputEmail.val();
                const username = inputUsername.val();
                const telefono = inputTelefono.val();
                const password = inputPassword.val();
                const confirmPassword = inputConfirmPassword.val();

                if (nombre.length == 0) {
                    message('warning', 'Introduce un nombre');
                    return;
                } else if (nombre.length < 3 || nombre.length > 20) {
                    message('warning', 'El nombre debe tener mas de 3 letras y menos de 20');
                    return;
                }

                if (apellido.length == 0) {
                    message('warning', 'Introduce un apellido');
                    return;
                } else if (apellido.length < 3 || apellido.length > 20) {
                    message('warning', 'El apellido debe tener mas de 3 letras y menos de 20');
                    return;
                }

                if (email.length == 0) {
                    message('warning', 'Introduce un email');
                    return;
                } else if (!validateEmail(email)) {
                    message('warning', 'Escribe un email valido');
                    return;
                }

                if (username.length == 0) {
                    message('warning', 'Introduce un nombre de usuario');
                    return;
                } else if (username.length < 5 || username.length > 20) {
                    message('warning', 'El nombre de usuario debe tener mas de 5 letras y menos de 20');
                    return;
                }

                if (telefono.length < 7) {
                    message('warning', 'Introduce un numero telefonico valido o deja el campo vacio');
                    return;
                }

                if (!validatePassword(password)) {
                    message('warning', 'Introduce una contraseña de almenos 10 caracteres con letras y numeros y los simbolos ". _ $" sin espacios en blanco.');
                    return;
                } else if (password !== confirmPassword) {
                    message('warning', 'Las contraseñas no coinciden');
                    return;
                }

                $.ajax({
                    url: "control/control-registro.php",
                    type: 'POST',
                    data: {
                        "operacion": "registro",
                        "nombre": nombre,
                        "apellido": apellido,
                        "email": email,
                        "username": username,
                        "telefono": telefono,
                        "password": password,
                        "confirmPassword": confirmPassword
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

                    if (!validateResponse(json, false)) {
                        return;
                    }

                    $.confirm({
                        icon: 'bi bi-check-circle-fill',
                        title: 'Bien hecho!',
                        content: 'El registro se realizo correctamente <br> Ahora puedes iniciar sesion',
                        type: 'green',
                        typeAnimated: true,
                        buttons: {
                            Ok: {
                                text: 'Ir al login',
                                action: function() {
                                    window.location.href = 'login.php';
                                }
                            }
                        }
                    })
                });
            });
        });
    </script>

</body>

</html>