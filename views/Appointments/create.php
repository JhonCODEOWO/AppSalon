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

        <form>
            <input type="hidden" name="idClient" id="idClient" value="<?php echo $user->id ?? null ?>">
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
        <button type="button" id="btnFinish">Agendar cita.</button>
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
        let services = []; //Variable to store every service from backend.
        let loading = true;
        showServices(loading, services);

        const idClient = document.querySelector('#idClient');
        const name = document.querySelector('#name');
        const date = document.querySelector('#date');
        const time = document.querySelector('#time');
        const btnFinish = document.querySelector('#btnFinish');

        //Form object data.
        const form = new window.App.Validator({
            idClient: [Number.parseInt(idClient.value) ?? null, [window.App.ValidationFunctions.required]],
            name: [name.value ?? '', [window.App.ValidationFunctions.required]],
            date: ["", [window.App.ValidationFunctions.required]],
            time: [""],
            services: [[]],
        }, true);

        date.addEventListener('change', (e) => {
            form.date = e.currentTarget.value;
        });

        time.addEventListener('change', (e) => {
            form.time = e.currentTarget.value;
        });

        //Send data and finish request form.
        btnFinish.addEventListener('click', (e) =>{
            form.validate();

            console.log(form.errors);
            return;
        })

        //Load services from backend
        window.App.services.getServices().then((services) => {
            loading = false;
            showServices(loading, services);
        }).catch((err) => {
            loading = false;
            showServices(loading, `${err}`);
        });

        //Render every service and add needed listeners
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
                serviceContainer.dataset.checked = false;

                serviceContainer.onclick = ((e) => {
                    selectService(e, service);
                });
                
                const serviceName = document.createElement('p');
                const servicePrice = document.createElement('p');

                serviceName.textContent = service.name;
                servicePrice.textContent = '$' + service.price;


                serviceName.classList.add('service__name');
                servicePrice.classList.add('service__price');

                serviceContainer.appendChild(serviceName);
                serviceContainer.appendChild(servicePrice);

                container.appendChild(serviceContainer);
            });
        }

        //Function to handle a HTMLElement click and add/remove every selection...
        function selectService(e, service){
            const element = e.currentTarget;
            const checked = element.dataset.checked;
            
            if(checked === 'false') {
                element.dataset.checked = true;
                element.classList.add('selected');
                form.services = [...form.services, service];
                return;
            };

            element.dataset.checked = false;
            element.classList.remove('selected');
            form.services = [...form.services.filter(s => s.id != service.id)];
        }
    })
</script>