<!doctype html>
<html lang="en">

<?php
include_once('inc/session.php');
include_once('inc/head.php');
include_once('inc/scripts.php');
include_once('inc/utility.php');

// si el usuario esta logado lo mando al index
if (isLogado()) {
    header("Location: index.php");
    exit;
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
                <h1>Pagina de login</h1>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-auto">
                <form>
                    <div class="mt-3 mb-3">
                        <label for="username" class="form-label">Nombre de Usuario</label>
                        <input type="text" class="form-control" id="username" minlength="5" maxlength="20" value="andredavid" required>
                    </div>
                    <div class="mt-3 mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="text" class="form-control" id="password" minlength="8" maxlength="64" value="4765178lp." required></input>
                    </div>
                    <div class="mt-3 mb-3">
                        <button type="button" id='pulsante-login' class="btn btn-primary">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            const inputUsername = $("#username");
            const inputPassword = $("#password");
            const pulsanteLogin = $("#pulsante-login");

            pulsanteLogin.on("click", function(e) {
                const username = inputUsername.val();
                const password = inputPassword.val();

                if (username.length == 0) {
                    message('warning', 'Introduce un nombre de usuario');
                    return;
                } else if (username.length < 5 || username.length > 20) {
                    message('warning', 'El nombre de usuario debe tener mas de 5 letras y menos de 20');
                    return;
                }

                if (!validatePassword(password)) {
                    message('warning', 'La contraseña tiene 10 caracteres con letras y numeros');
                    return;
                }

                $.ajax({
                    url: "control/control-login.php",
                    type: 'POST',
                    data: {
                        "operacion": "login",
                        "username": username,
                        "password": password
                    }
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
                    } else {
                        window.location.href = 'index.php';
                    }
                });
            });
        });
    </script>

</body>

</html>