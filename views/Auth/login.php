<form action="/login" method="post" class="text-center">
    <h1 class="text-center">Iniciar sesión</h1>
    <p>Coloca tus credenciales para iniciar sesión.</p>

    <fieldset class="input-fieldset">
        <legend for="email">Email</legend>
        <input 
            type="email" 
            name="email" 
            id="email"
            placeholder="Escribe el correo de tu cuenta"
            value="<?php echo arrayFrom($old, 'email') ?>"
        >
        <?php if(isset($errors)) :?>
            <p class="text-start error"><?php echo $errors->getFrom('email') ?></p>
        <?php endif?>
    </fieldset>
    <fieldset class="input-fieldset">
        <legend for="password">Contraseña</legend>
        <input 
            type="password" 
            name="password" 
            id="password"
            placeholder="Tu contraseña"
        >
        <?php if(isset($errors)) :?>
            <p class="text-start error"><?php echo $errors->getFrom('password') ?></p>
        <?php endif?>
    </fieldset>
    <button 
        type="submit"
        class="button button-info"
    >
        Iniciar sesión
    </button>
    <div class="flex justify-between w-full mt-3">
        <a href="/forgot-password">¿Olvidaste tu contraseña?</a>
        <a href="/create-account">Crear cuenta</a>
    </div>
</form>