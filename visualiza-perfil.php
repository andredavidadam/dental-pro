<!doctype html>
<html lang="en">

<?php
include('inc/session.php');
include('inc/head.php');
include('inc/scripts.php');
include('inc/utility.php');
permiso(array('rainweb' => '', 'dentalpro' => ''));
?>
<title>Rainweb - perfil</title>

<body>
    <?php include('componentes/componente-navbar-menu.php'); ?>
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
    </div>
</body>

</html>