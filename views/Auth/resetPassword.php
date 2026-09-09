<form action="/reset-password" method="post">
    <?php if(isset($errors)): ?>
        <p class="error alert">
            <?php echo $errors->getFrom('token') ?>
        </p>
    <?php endif ?>
    <h1 class="text-center">Restablece tu contraseña</h1>
    <p>Ingresa tu nueva contraseña, asegúrate de no olvidarla esta vez pendejo :b</p>

    <fieldset class="input-fieldset">
        <legend>Nueva contraseña</legend>
        <input type="password" name="password" id="password" placeholder="Contraseña de 8 caracteres...">
        <?php if(isset($errors)): ?>
            <p class="error">
                <?php echo $errors->getFrom('password') ?>
            </p>
        <?php endif ?>
    </fieldset>
    <fieldset class="input-fieldset">
        <legend>Repite la nueva contraseña</legend>
        <input type="password" name="password_confirmation" id="password" placeholder="Confirma tu nueva contraseña">
        <?php if(isset($errors)): ?>
            <p class="error">
                <?php echo $errors->getFrom('password_confirmation') ?>
            </p>
        <?php endif ?>
    </fieldset>

    <?php if(isset($token)) :?>
        <input type="hidden" name="token" value="<?php echo $token ?>">
    <?php endif ?>

    <button type="submit" class="button button-success">
        Actualizar contraseña.
    </button>
</form>