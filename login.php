<!doctype html>
<html lang="en">

<?php
include_once('inc/session.php');
include_once('inc/head.php');
include_once('inc/scripts.php');
include_once('inc/utility.php');

// si el usuario esta logado lo mando al index
if (isset($_SESSION['is_logado']) || $is_logado === true) {
    header("Location: index.php");
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
                        <input type="text" class="form-control" id="password" minlength="8" maxlength="64" value="12345678" required></input>
                    </div>
                    <div class="mt-3 mb-3">
                        <button id='pulsante-login' class="btn btn-primary">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            let inputUsername = $("#username");
            let inputPassword = $("#password");
            let pulsanteLogin = $("#pulsante-login");

            pulsanteLogin.on("click", function(e) {
                login()
            });

            function login() {
                let username = inputUsername.val();
                let password = inputPassword.val();

                $.ajax({
                    url: "control/control-login.php",
                    type: 'POST',
                    data: {
                        "operacion": "login",
                        "username": username,
                        "password": password
                    },
                }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
                    alert('el inicio de sesion no se puede realizar en este momento');
                }).done(function(response) {
                    let json = JSON.parse(response);
                    if (json['status'] == 'success') {
                        window.location.href = "index.php";
                    } else {
                        //alert(json['mensaje']);
                    }
                });
                return;
            }

        });
    </script>

</body>

</html>