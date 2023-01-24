<!doctype html>
<html lang="en">

<?php
include('inc/session.php');
include("inc/head.php");
include("inc/scripts.php");
include("inc/utility.php");

if ($is_logado_session === true && $tipologia_session!==Tipologia::Rainweb) {
    echo "<script>window.location.href = 'dashboard.php';</script>";
    return;
}
?>

<body>
    <div class="container">
        <div class="row">
            <div class="col">
                <h1>Pagina de registro</h1>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-auto">
                <form>
                    <div class="mt-3 mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="input-nombre" value="marco" required>
                    </div>
                    <div class="mt-3 mb-3">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input type="text" class="form-control" id="input-apellido" value="aurelio" required>
                    </div>
                    <div class="mt-3 mb-3">
                        <label for="email" class="form-label">email</label>
                        <input type="email" class="form-control" id="input-email" value="marcoaurelio@gmail.com" required>
                    </div>
                    <div class="mt-3 mb-3">
                        <label for="username" class="form-label">Nombre de Usuario</label>
                        <input type="text" class="form-control" id="input-username" value="marcoaurelio" required>
                    </div>
                    <div class="mt-3 mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="text" class="form-control" id="input-password" value="12345678" required></input>
                    </div>
                    <div class="mt-3 mb-3">
                        <label for="confirm-password" class="form-label">Confirma Password</label>
                        <input type="text" class="form-control" id="input-confirm-password" value="12345678" required></input>
                    </div>
                    <div class="mt-3 mb-3">
                        <button id='pulsante-registro' class="btn btn-primary">Registrate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            let inputNombre = $("#input-nombre");
            let inputApellido = $("#input-apellido");
            let inputEmail = $("#input-email");
            let inputUsername = $("#input-username");
            let inputPassword = $("#input-password");
            let inputConfirmPassword = $("#input-confirm-password");
            let pulsanteRegistro = $("#pulsante-registro");

            pulsanteRegistro.on("click", function(e) {
                e.preventDefault();
                registro();
            });

            function registro() {
                let nombre = inputNombre.val();
                let apellido = inputApellido.val();
                let email = inputEmail.val();
                let username = inputUsername.val();
                let password = inputPassword.val();
                let confirmPassword = inputConfirmPassword.val();

                if (nombre.length < 5 || nombre.length > 20) {
                    alert("r el nombre debe tener minimo 5 caracteres y maximo 20");
                    return;
                }
                if (password.length < 8 || password.length > 32) {
                    alert("la contraseña debe tener almenos 8 caracteres");
                    return;
                }
                if (password !== confirmPassword) {
                    alert("las contraseñas no coinciden");
                    return;
                }
                if (nombre == '') {
                    alert("debes escribir tu nombre");
                    return;
                }
                if (apellido == '') {
                    alert("debes escribir tu apellido");
                    return;
                }
                if (email == '') {
                    alert("debes escribir un email");
                    return;
                }
                if (username == '') {
                    alert("debes escribir un nombre de usuario");
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
                        "password": password,
                        "confirmPassword": confirmPassword
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