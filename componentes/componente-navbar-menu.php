<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">Inicio</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- lo hacemos bien -->
            <?php
            switch ($tipologia_session) {
                case Tipologia::Rainweb:
            ?>
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
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
                    </ul>
                <?php
                    include('componente-dropdown-perfil.php');
                    break;
                case Tipologia::DentalPro:
                ?>
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
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
                    </ul>

                <?php
                    include('componente-dropdown-perfil.php');
                    break;
                default:
                ?>
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
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
                    </ul>
            <?php
                    break;
            }
            //include('componente-dropdown-perfil.php');
            ?>
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