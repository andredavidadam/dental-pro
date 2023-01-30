<!doctype html>
<html lang="en">

<?php
include('inc/session.php');
include('inc/head.php');
include('inc/scripts.php');
include('inc/utility.php');

?>
<title>Rainweb - Index</title>

<body>
    <?php
    include('componentes/componente-navbar-menu.php');
    ?>

    <div class="container">
        <div class="row">
            <div class="col">
                <h1>index</h1>
            </div>
        </div>
        <!-- botones -->
        <div class="row">
            <div class="col-4 me-2 ms-2">
                <a type="button" class="btn btn-primary" href="login.php">Login</a>
            </div>
            <div class="col-4 me-2 ms-2">
                <a type="button" class="btn btn-primary" href="registro.php">Registro</a>
            </div>
        </div>
    </div>
</body>

</html>