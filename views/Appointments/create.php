<h1>Crear cita</h1>
<p>Agenda tu cita en 3 simples pasos.</p>


<div id="steps">
    <nav class="tabs">
        <button type="button" data-step="1" class="tab">
            Servicios
        </button>
        <button type="button" data-step="2" class="tab">
            Datos de la cita
        </button>
        <button type="button" data-step="3" class="tab">
            Confirmación
        </button>
    </nav>

    <div id="step-1">
        <h2>Servicios</h2>
        <p>Elige los servicios deseados.</p>
        <div id="services"></div>
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

    <nav class="navigation">
        <button id="next" class="button button-info">
            Siguiente
        </button>
        <button id="previous" class="button button-info">
            Anterior
        </button>
    </nav>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        //Get every HTML element necessary
        const step1 = document.querySelector('#step-1');
        const step2 = document.querySelector('#step-2');
        const step3 = document.querySelector('#step-3');
        const tabs = document.querySelectorAll('.tab');
        const next = document.querySelector('#next');
        const prev = document.querySelector('#previous');

        //Global variables to manage
        const steps = [step1, step2, step3];
        const totalSteps = tabs.length-1;
        let tabIndex = 0;

        //Main method to manage every step and styles...
        manageStep(steps, tabs,tabIndex);

        next.addEventListener('click', () => {
            if(tabIndex == totalSteps) return;
            tabIndex += 1;
            
            manageStep(steps, tabs,tabIndex);
        })

        prev.addEventListener('click', () => {
            if(tabIndex == 0) return;

            tabIndex -= 1;

            manageStep(steps, tabs,tabIndex);
        })
    })

    function manageStep(elements, tabs, active) {
        activeWorkflow = elements[active];
        activeTab = tabs[active];

        tabs.forEach(element => {
            if(element != active) element.classList.remove('active');
        });

        inactiveWorkflows = elements.filter((workflow, index) => index != active);
        hideElements(inactiveWorkflows);

        activeWorkflow.style.display = "block";
        activeTab.classList.add('active');
    }

    function hideElements(elements, reverse = false) {
        elements.forEach(element => {
            element.style.display = 'none';
        });
    }
</script>