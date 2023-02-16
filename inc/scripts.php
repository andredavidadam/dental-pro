<!-- Bootstrap core CSS -->
<script src="js/bootstrap.bundle.min.js"></script>

<!-- JQuery -->
<script src="js/jquery-3.6.3.min.js"></script>

<!-- JQuery confirm -->
<script src="js/jquery-confirm.min.js"></script>

<!-- DataTables -->
<script src="js/jquery.dataTables.min.js"></script>

<!-- popper -->
<script src="js/popper.js"></script>

<!-- set default jquery-confirm -->
<script>
    jconfirm.defaults = {
        title: 'Hello',
        titleClass: '',
        type: 'default',
        typeAnimated: true,
        draggable: true,
        dragWindowGap: 15,
        dragWindowBorder: true,
        animateFromElement: true,
        smoothContent: true,
        content: 'Are you sure to continue?',
        buttons: {},
        defaultButtons: {
            Ok: {
                action: function() {}
            },
            close: {
                action: function() {}
            },
        },
        contentLoaded: function(data, status, xhr) {},
        icon: '',
        lazyOpen: false,
        bgOpacity: null,
        theme: 'light',
        animation: 'scale',
        closeAnimation: 'top',
        animationSpeed: 400,
        animationBounce: 1,
        rtl: false,
        container: 'body',
        containerFluid: false,
        backgroundDismiss: false,
        backgroundDismissAnimation: 'shake',
        autoClose: false,
        closeIcon: null,
        closeIconClass: false,
        watchInterval: 100,
        columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
        boxWidth: '50%',
        scrollToPreviousElement: true,
        scrollToPreviousElementAnimate: true,
        useBootstrap: true,
        offsetTop: 40,
        offsetBottom: 40,
        bootstrapClasses: {
            container: 'container',
            containerFluid: 'container-fluid',
            row: 'row',
        },
        onContentReady: function() {},
        onOpenBefore: function() {},
        onOpen: function() {},
        onClose: function() {},
        onDestroy: function() {},
        onAction: function() {}
    };

    function message(tipo, mensaje) {
        switch (tipo) {
            case 'info':
                var icon = 'bi bi-info-circle-fill';
                var title = 'Informacion';
                var type = 'blue';
                var pulsante = 'btn-info';
                break;
            case 'warning':
                var icon = 'bi bi-exclamation-triangle-fill';
                var title = 'Atencion!';
                var type = 'red';
                var pulsante = 'btn-danger';
                break;
            case 'error':
                var icon = 'bi bi-x-circle-fill';
                var title = 'Error!';
                var type = 'red';
                var pulsante = 'btn-danger';
                break;
            case 'success':
                var icon = 'bi bi-check-circle-fill';
                var title = 'Bien hecho!';
                var type = 'green';
                var pulsante = 'btn-success';
                break;
            default:
                break;
        }
        $.confirm({
            icon: icon,
            title: title,
            content: mensaje,
            type: type,
            typeAnimated: true,
            buttons: {
                tryAgain: {
                    text: 'Ok',
                    btnClass: pulsante
                },
            }
        });
    }



    // funciones utiles de javascript

    // funcion que valida un email
    function validateEmail(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    // funcion que valida una contraseña de letras y numeros de almenos 10 caracteres
    function validatePassword(password) {
        const re = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[\.\_\$]).{8,32}$/;
        return re.test(password);
    }

    // funcion que analiza el response recibido desde ajax en caso de error
    function validateResponse(json) {
        let isValid = false;
        switch (json['status']) {
            case 'info':
                message('info', json['mensaje']);
                break;
            case 'warning':
                message('warning', json['mensaje']);
                break;
            case 'error':
                message('error', json['mensaje']);
                break;
            case 'success':
                isValid = true;
                break;
        }
        return isValid;
    }
    // funcion que agrega la visualizacion para un campo 
    // llenado correctamente o no
    function invalidInput(input, mensaje) {
        message('warning', mensaje);
        input.addClass("is-invalid");
        input.focus();

    }

    // limpia el campo input si este es modificado
    function pressKey(input) {
        input.each(function() {
            $(this).keyup(function(e) {
                $(this).removeClass("is-invalid");
            });
        });
    }

    // limpia los campos del formulario
    function cleanForm(input) {
        input.each(function() {
            $(this).val('');
        });
    }
</script>