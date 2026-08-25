<form action="/createAccount" method="post">
    <h1 class="text-center">Crear cuenta</h1>
    <p>Llena correctamente los campos para crear una cuenta.</p>

    <fieldset class="input-fieldset">
        <legend for="nombre">Tu nombre</legend>
        <input 
            type="text" 
            name="nombre" 
            id="nombre"
            placeholder="Tu nombre"
        >
    </fieldset>
    <fieldset class="input-fieldset">
        <legend for="apellido">Apellido(s)</legend>
        <input 
            type="text" 
            name="apellido" 
            id="apellido"
            placeholder="Tu(s) apellido(s)"
        >
    </fieldset>
    <fieldset class="input-fieldset">
        <legend for="telefono">Teléfono</legend>
        <input 
            type="tel" 
            name="telefono" 
            id="telefono"
            placeholder="Escribe un número de teléfono activo y válido."
        >
    </fieldset>
    <fieldset class="input-fieldset">
        <legend for="email">Correo electrónico</legend>
        <input 
            type="email" 
            name="email" 
            id="email"
            placeholder="Correo electrónico válido y activo."
        >
    </fieldset>
    <fieldset class="input-fieldset">
        <legend for="password">Contraseña</legend>
        <input 
            type="password" 
            name="password" 
            id="password"
            placeholder="Contraseña para la cuenta."
        >
    </fieldset>
    <fieldset class="input-fieldset">
        <legend for="password_confirmation">Repite tu contraseña</legend>
        <input 
            type="password" 
            name="password_confirmation" 
            id="password_confirmation"
            placeholder="Escribe tu contraseña nuevamente para confirmarla."
        >
    </fieldset>

    <button 
        type="submit"
        class="button button-success"
    >
        Crear cuenta
    </button>

    <a href="/login">
        ¿Ya tienes cuenta? Inicia sesión aquí.
    </a>
</form>