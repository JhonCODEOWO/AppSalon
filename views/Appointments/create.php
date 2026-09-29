<h1>Crear cita</h1>
<p>Agenda tu cita en 3 simples pasos.</p>


<div id="steps">
    <nav class="tabs">
        <button type="button" class="tab">
            Servicios
        </button>
        <button type="button" class="tab">
            Datos de la cita
        </button>
        <button type="button" class="tab">
            Confirmación
        </button>
    </nav>

    <div class="step">
        <h2>Servicios</h2>
        <p>Elige los servicios deseados.</p>
        <div id="services" class="services"></div>
    </div>
    
    <div class="step">
        <h2>Tus datos y cita.</h2>

        <form action="">
            <fieldset class="input-fieldset">
                <legend>Nombre:</legend>
                <input 
                    type="text" 
                    name="name" 
                    id="name"
                    placeholder="Tu nombre"
                    value="<?php echo $user->name ?? null ?>"
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

    <div class="step">
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
        let services = [];
        let loading = true;
        showServices(loading, services);

        window.App.services.getServices().then((services) => {
            loading = false;
            showServices(loading, services);
        }).catch((err) => {
            loading = false;
            showServices(loading, `${err}`);
        });

        function showServices(loading, content){
            const container = document.querySelector("#services");

            container.innerHTML = ``;
            if(loading) {
                container.innerHTML = `
                    <p>Cargando...</p>
                `
                return;
            }

            if (!Array.isArray(services)) {
                container.innerHTML = `
                    <p>
                        ${content}
                    </p>
                `
                return;
            };

            content.forEach(service => {
                const serviceContainer = document.createElement('div');
                serviceContainer.classList.add('service');

                const serviceCheckbox = document.createElement('input');
                serviceCheckbox.type = 'checkbox';
                serviceCheckbox.name = 'service';
                serviceCheckbox.value = service.id;

                const serviceName = document.createElement('p');
                const servicePrice = document.createElement('p');

                serviceName.textContent = service.name;
                servicePrice.textContent = '$' + service.price;


                serviceName.classList.add('service__name');
                servicePrice.classList.add('service__price');


                serviceContainer.appendChild(serviceCheckbox);
                serviceContainer.appendChild(serviceName);
                serviceContainer.appendChild(servicePrice);

                container.appendChild(serviceContainer);
            });
        }
    })
</script>