<h1>Crear cita</h1>
<p>Elige los servicios a continuación</p>


<div id="steps">
    <div id="step-1">
        <h2>Servicios</h2>
    </div>
    
    <div id="step-2">
        <h2>Tus datos y cita.</h2>

        <form action="">
            <fieldset class="input-fieldset">
                <legend>Nombre:</legend>
                <input 
                    type="text" 
                    name="name" 
                    id="name"
                    placeholder="Tu nombre"
                    value="<?php echo $name ?>"
                    disabled
                >
            </fieldset>

            <fieldset class="input-fieldset">
                <legend>Fecha</legend>
                <input 
                    type="date" 
                    name="date" 
                    id="date"
                    placeholder="Fecha de la cita"
                >
            </fieldset>

            <fieldset class="input-fieldset">
                <legend>Hora</legend>
                <input 
                    type="time" 
                    name="time" 
                    id="time"
                    placeholder="Fecha de la cita"
                >
            </fieldset>
        </form>
    </div>

    <div id="step-3">
        <h2>Resumen.</h2>
        <p>Verifica tus datos y finaliza.</p>
    </div>
</div>