<?php if($errorMessage != null):?>
    <p><?php echo $errorMessage ?></p>
<?php endif ?>
<form action="<?php echo isset($service)? "/services/update/$service->id": "/services/create"?>" method="post">
    <fieldset class="input-fieldset">
        <legend>Nombre:</legend>
        <input type="text" name="name" id="name" placeholder="Nombre del servicio" value="<?php echo (isset($service))? $service->name: old("name") ?? null ?>">
        <p class="error"><?php echo error("name") ?></p>
    </fieldset>
    <fieldset class="input-fieldset">
        <legend>Precio:</legend>
        <input type="number" step=".01" name="price" id="price" placeholder="Precio con decimales para el servicio" value="<?php echo (isset($service))? $service->price: old("price") ?? null  ?>">
        <p class="error"><?php echo error("price") ?></p>
    </fieldset>

    <input type="submit" value="<?php echo isset($service)? "Actualizar": "Crear servicio"?>" class="button button-success">
</form>