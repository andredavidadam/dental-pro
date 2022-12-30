<!doctype html>
<html lang="en">

<?php
include('inc/session.php');
include('inc/head.php');
include('inc/scripts.php');
include('inc/utility.php');

print_r($_SESSION);
?>
<title>Rainweb - Dashboard</title>

<body>
    <div class="container">
        <div class="row">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">Navbar</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="#">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Link</a>
                            </li>
                            
                        </ul>
                        <?php include('componentes/componente-dropdown-perfil.php'); ?>
                        
                    </div>
                </div>
            </nav>
        </div>
        <div class="row">
            <div class="col">
                <h1>Dashboard</h1>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-auto">
                <div class="mt-3 mb-3">
                    <button id='pulsante-logout' class="btn btn-primary">Logout</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            let pulsanteLogout = $("#pulsante-logout");

            pulsanteLogout.on("click", function(e) {
                e.preventDefault();
                logout()
            });

            function logout() {
                $.ajax({
                    url: "control/control-logout.php",
                    type: 'POST',
                    data: {
                        "operacion": "logout",
                    },
                }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
                    alert('el inicio de sesion no se puede realizar en este momento');
                }).done(function(response) {
                    let json = JSON.parse(response);
                    if (json['status'] == 'success') {
                        window.location.href = "index.php";
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