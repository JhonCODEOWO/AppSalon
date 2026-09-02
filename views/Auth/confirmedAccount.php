<?php if ($errors->hasErrors()): ?>
    <h1>Error, no se ha podido verificar la cuenta.</h1>

    <p>Posiblemente entraste a este link por error o el token no es válido.</p>

    <div class="mt-3">
        <?php foreach($errors->getAllFrom('token') as $error ): ?>
            <p class="error"><?php echo $error ?></p>
        <?php endforeach ?>
    </div>
<?php endif ?>

<?php if(!$errors->hasErrors()): ?>
    <p>Cuenta verificada exitosamente.</p>

    <a href="/login">Iniciar sesión</a>
<?php endif ?>