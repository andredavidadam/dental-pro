<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">Inicio</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <!-- lo hacemos bien -->
                <?php
                switch ($tipologia_session) {
                    case Tipologia::Rainweb:
                ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Menu
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="visualiza-log.php">Visualiza Log</a></li>
                                <!-- <hr class="dropdown-divider"> -->
                                <li><a class="dropdown-item" href="visualiza-perfil.php">Mi Perfil</a></li>
                            </ul>
                        </li>
                    <?php
                        break;
                    case Tipologia::DentalPro:
                    ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Mis Pacientes
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#">Agenda</a></li>
                                <!-- <hr class="dropdown-divider"> -->
                                <li><a class="dropdown-item" href="visualiza-perfil.php">Mi Perfil</a></li>
                            </ul>
                        </li>
                <?php
                        break;
                }
                ?>

            </ul>

            <div class="dropdown">
                <button type="button" class="btn btn-info dropdown-toggle " id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    Hola, <?php echo ucfirst($username_session); ?>!
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <?php
                    echo '<li><a class="dropdown-item" onclick="window.location.href=\'visualiza-perfil.php\'" href="#">Mi Perfil</a></li>';
                    echo '<li><a class="dropdown-item text-danger fw-bold" onclick="Logout()" href="#">Logout</a></li>';

                    if (1 === 1) {
                        echo "<li><a class='dropdown-item fw-bold' href='#'>ip_session = $ip_session</a></li>";
                        echo "<li><a class='dropdown-item fw-bold' href='#'>id_session = $id_session</a></li>";
                        echo "<li><a class='dropdown-item fw-bold' href='#'>is_logado = $is_logado_session</a></li>";
                        echo "<li><a class='dropdown-item fw-bold' href='#'>id_usuario = $id_usuario_session</a></li>";
                        echo "<li><a class='dropdown-item fw-bold' href='#'>email = $email_session</a></li>";
                        echo "<li><a class='dropdown-item fw-bold' href='#'>username = $username_session</a></li>";
                        echo "<li><a class='dropdown-item fw-bold' href='#'>tipologia = $tipologia_session</a></li>";
                        echo "<li><a class='dropdown-item fw-bold' href='#'>rol = $rol_session</a></li>";
                        echo "<li><a class='dropdown-item fw-bold' href='#'>token = $token_session</a></li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</nav>


<script>
    function Logout() {
        event.preventDefault();
        $.ajax({
            type: 'post',
            url: 'control/control-logout.php',
            success: function(response) {
                window.location.href = "index.php";
            }
        });
    }
</script>