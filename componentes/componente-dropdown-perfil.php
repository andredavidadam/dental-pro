<div class="dropdown">
    <button type="button" class="btn btn-info dropdown-toggle " id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
        Hola, <?php echo ucfirst($username_session); ?>!
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
        <?php
        echo '<li><a class="dropdown-item" onclick="window.location.href=\'visualiza-perfil.php\'" href="#">Mi Perfil</a></li>';
        echo '<li><a class="dropdown-item text-danger fw-bold" onclick="Logout()" href="#">Logout</a></li>';

        if (1 === 1) {
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