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
                    min="<?php echo date('Y-m-d'); ?>"
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
        <div id="errors" class=" text-white p-1.5 text-xl flex flex-col items-center gap-y-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" viewBox="0 0 24 24">
                <path d="M0 0h24v24H0z" fill="none" />
                <path fill="currentColor" fill-rule="evenodd" d="M14.543 2.598a2.821 2.821 0 0 0-5.086 0L1.341 18.563C.37 20.469 1.597 23 3.883 23h16.234c2.286 0 3.511-2.53 2.542-4.437zM12 8a1 1 0 0 1 1 1v5a1 1 0 1 1-2 0V9a1 1 0 0 1 1-1m0 8.5a1 1 0 0 1 1 1v.5a1 1 0 1 1-2 0v-.5a1 1 0 0 1 1-1" clip-rule="evenodd" />
            </svg>
            <p>Al parecer no has llenado la información necesaria o la información que colocaste es incorrecta.</p>
            <p class="w-full text-start">Revisa la información e intenta de nuevo.</p>
        </div>
        <div id="resume" class="hidden">
            <h2>Resumen.</h2>
            <p>Verifica tus datos y finaliza.</p>
        </div>
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
        let prevNode = null; //It will store a reference to the last resume node to delete it previously to create an append a new one.
        
        let loading = true;
        showServices(loading, services);

        const idClient = document.querySelector('#idClient');
        const name = document.querySelector('#name');
        const date = document.querySelector('#date');
        const time = document.querySelector('#time');

        const errors = document.querySelector('#errors');
        const resume = document.querySelector('#resume');

        //Form object data.
        const form = new window.App.Validator({
            idClient: [Number.parseInt(idClient.value) ?? null, [
                [window.App.ValidationFunctions.required]
            ]],
            name: ['', [
                [window.App.ValidationFunctions.required]
            ]],
            date: ["", [
                [window.App.ValidationFunctions.required],
                [onlyWeekDays],
                [minDate, "today"]
            ]],
            time: ["", [
                [window.App.ValidationFunctions.required],
                [ableHours, '10-18'],
            ]],
            services: [[], [
                [window.App.ValidationFunctions.required]
            ]],
        }, true);

        //Listen for validator-success event to render final form phases.
        form.addEventListener('validator-success', ({detail}) => {
            const {name, date, time, services, invalid} = detail;

            if(invalid){
                resume.classList.add('hidden');
                errors.classList.remove('hidden');
                return;
            }

            errors.classList.add('hidden');
            resume.classList.remove('hidden');
            if(prevNode) prevNode.remove();
            prevNode = showResume(name, date, services, time);

            resume.appendChild(prevNode);
        })

        //Load services from backend
        window.App.services.getServices().then((services) => {
            loading = false;
            showServices(loading, services);
        }).catch((err) => {
            loading = false;
            showServices(loading, `${err}`);
        });

        /**
         * Returns a HTMLNode with a HTML of a resume.
         * @param {string} name
         * @param {string} date
         * @param {array} services
         * @param {string} hour
         * @returns {HTMLDivElement}
         */
        function showResume(name, date, services, hour){
            const dateInstance = new Date(date);
            const month = dateInstance.getMonth();
            const day = dateInstance.getDate() + 2;
            const year = dateInstance.getFullYear();

            const formattedDate = new Date(Date.UTC(year, month, day)).toLocaleDateString('es-MX', {
                weekday: 'long',
                month: 'long',
                day: 'numeric',
                year: 'numeric'
            });

            const resumeDiv = document.createElement('div');
            resumeDiv.classList.add('resume');
            resumeDiv.innerHTML = `
                <div class="resume header">
                    <p>${name}</p> 
                    <p>Cita para el: ${formattedDate} a las ${hour}</p>
                </div>
                
                ${services.map(s => 
                    `<div class="resume item">
                        <p>
                            Servicio: ${s.value.name}
                        </p>
                        <p>
                            Precio: $${s.value.price}
                        </p>
                    </div>`
                ).join(' ')}

                <p class="resume total">
                    Total: <span>$${services.map(s => s.value.price).reduce((accumulator, curr) => accumulator + curr, 0)}</span>
                </p>
            `;
            
            const btnFinish = document.createElement('button');
            btnFinish.type = 'submit';
            btnFinish.classList.add('button', 'button-success');
            btnFinish.textContent = 'Agendar cita';
            
            //Finish button action...
            btnFinish.onclick = (e) => {
                e.preventDefault();
                form.markAllAsTouched();
                console.log(form.mapToPlainObject());
            }

            resumeDiv.appendChild(btnFinish);

            return resumeDiv;
        }

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

            content.forEach((service, index) => {
                const serviceContainer = document.createElement('div');
                serviceContainer.classList.add('service');
                serviceContainer.dataset.checked = false;

                serviceContainer.onclick = ((e) => {
                    selectService(e, service, index);
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
        function selectService(e, service, index){
            const element = e.currentTarget;
            const checked = element.dataset.checked;
            let indexToDelete = null;
            
            if(checked === 'false') {
                element.dataset.checked = true;
                element.classList.add('selected');
                indexToDelete = form.addArrayValue('services', service, index);
                form.validate();
                return;
            };

            element.dataset.checked = false;
            element.classList.remove('selected');
            form.removeArrayValue('services', index);
            form.validate();
        }

        function minDate(value, minDate) {
            const selectedDate = new Date(`${value}T00:00:00`);

            const min = minDate === "today"
                ? new Date().setHours(0, 0, 0, 0)
                : new Date(`${minDate}T00:00:00`).getTime();

            const operation = selectedDate.getTime() >= min;

            return [
                operation,
                `Please select a date on or after ${minDate}.`,
                "minDate"
            ];
        }

        function ableHours(value, ableHours){
            const [hour, minutes] = value.split(':');
            const [initHour, finalHour] = ableHours.split('-');

            const operation = hour >= initHour && hour <= finalHour;
            return [operation, `Select only a valid hour between: ${ableHours} hours.`];
        }

        function onlyWeekDays(value, params){
            const utcDay = new Date(value).getUTCDay();
            const operation = ![6,0].includes(utcDay);
            return [operation, "You can't select weekend days.", "onlyWeekDays"];
        }
    })
</script>