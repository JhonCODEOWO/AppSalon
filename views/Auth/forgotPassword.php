<form action="/forgot-password" method="post">
    <h1 class="text-center">Recuperar contraseña</h1>
    <p>Escribe tu correo, nosotros nos encargaremos de enviarte un email con los siguientes pasos.</p>

    <?php if(isset($success)): ?>
        <p class="success"><?php echo $success ?></p>
    <?php endif?>

    <fieldset class="input-fieldset">
        <legend for="email">Email</legend>
        <input type="email" name="email" id="email" placeholder="Escribe el email de la cuenta a recuperar.">
        <?php if(isset($errors)): ?>
            <p class="error"><?php echo $errors->getFrom('email') ?></p>
        <?php endif ?>
    </fieldset>

    <button type="submit" class="button button-warning">Buscar y recuperar cuenta.</button>
</form>

<p class="mt-5">
    ¿No es lo que deseabas hacer? <a href="/login" class="font-bold">inicia sesión</a>.
</p>