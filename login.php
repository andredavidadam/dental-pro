<!doctype html>
<html lang="en">

<?php
include('inc/session.php');
include('inc/head.php');
include('inc/scripts.php');
include('inc/utility.php');

stampa($_SESSION);


if ($is_logado_session === true) {
    echo "<script>window.location.href = 'dashboard.php';</script>";
    return;
}
?>

<body>
    <div class="container">
        <div class="row">
            <div class="col">
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
                e.preventDefault();
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
                        window.location.href = "dashboard.php";
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