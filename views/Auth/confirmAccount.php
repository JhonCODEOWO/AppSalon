<h1>Hola, estás a un solo paso más de poder utilizar tu cuenta.</h1>
<?php if(error('token') != null) :?>
    <p class="error alert"><?php echo error('token') ?></p>
<?php endif ?>
<p>Pulsa en el botón de abajo para confirmar tu cuenta</p>
<form action="/confirm-account" method="post">
    <input type="hidden" name="token" value="<?php echo $token ?>">
    <button type="submit" class="button button-success">
        Confirma mi cuenta
    </button>
</form>