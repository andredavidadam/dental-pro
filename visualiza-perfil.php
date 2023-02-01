<!doctype html>
<html lang="en">

<?php
include('inc/session.php');
include('inc/head.php');
include('inc/scripts.php');
include('inc/utility.php');
if (!isLogado()) {
    exit;
}
?>
<title>Rainweb - perfil</title>

<body>
    <?php include('componentes/componente-navbar-menu.php'); ?>
    <div class="container">
        <section class="hero-2 position-relative">
            <div class="row justify-content-center">
                <div class="col-6">
                    <div class="text-center title mb-5 mt-5">
                        <p class="text-muted text-uppercase fw-normal mb-2">Rainweb</p>
                        <h3 class="mb-3">Visualiza Perfil</h3>
                    </div>
                </div>
            </div>
        </section>
        <div class="row">
            <div class="col-6">
                aqui la foto
            </div>
            <div class="col-6">
                <?php
                $sql = "SELECT username, nombre, apellido, email, telefono, tipologia, rol FROM usuario WHERE id = $id_usuario_session;";
                $loop = mysqli_query($dbDentalPro, $sql);
                while ($row = mysqli_fetch_assoc($loop)) {
                    $username = $row['username'];
                    $nombreCompleto = ucfirst($row['nombre']) . ' ' . ucfirst($row['apellido']);
                    $email = $row['email'];
                    $telefono = $row['telefono'];
                    $nivelAcceso = ucfirst($row['rol']) . ' ' . ucfirst($row['tipologia']);
                }
                ?>
                <h3 class="text-primary"><?php echo $nombreCompleto ?></h3>
                <p class="text-muted fw-normal mb-2"><?php echo $nivelAcceso ?></p>
                <hr>
                <label class="form-label" for="username">Nombre de Usuario</label>
                <p id="username"><b><?php echo $username ?></b></p>
                <label class="form-label" for="email">email</label>
                <p id="email"><b><?php echo $email ?></b></p>
                <label class="form-label" for="email">telefono</label>
                <p id="email"><b><?php echo $telefono ?></b></p>
                <div class="row">
                    <div class="col">
                        <button class="btn btn-info mt-3 ms-3 me-3">Modificar Datos</button>
                        <button class="btn btn-info mt-3 ms-3 me-3">Cambia Password</button>
                    </div>
                    
                </div>
            </div>

        </div>
</body>

</html>