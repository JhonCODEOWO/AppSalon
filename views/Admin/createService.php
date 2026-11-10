<h1>Crear nuevo servicio</h1>

<form action="/services/create" method="post">
    <fieldset class="input-fieldset">
        <legend>Nombre:</legend>
        <input type="text" name="name" id="name" placeholder="Nombre del servicio" value="<?php echo old("name") ?>">
        <p class="error"><?php echo error("name") ?></p>
    </fieldset>
    <fieldset class="input-fieldset">
        <legend>Precio:</legend>
        <input type="number" name="price" id="price" placeholder="Precio con decimales para el servicio" value="<?php echo old("price") ?>">
        <p class="error"><?php echo error("price") ?></p>
    </fieldset>

    <input type="submit" value="Crear servicio" class="button button-success">
</form>