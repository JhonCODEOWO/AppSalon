<h1>Panel de administración</h1>
<h2>Bienvenido <?php echo Auth()::user()->name ?>  </h1>

<a href="/createAppointment">Crear cita</a>

<div>
    <form id="searchForm" class="flex justify-between gap-x-2">
        <fieldset class="input-fieldset">
            <label for="date">Fecha</label>
            <input type="date" name="date" id="date" value="<?php echo date('Y-m-d') ?>">
        </fieldset>
        <input type="submit" value="Buscar...">
    </form>
</div>

<h3>Citas</h3>
<div id="appointments">
    <div id="loading">
        <p>Cargando...</p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const divAppointments = document.querySelector('#appointments');
        const searchForm = document.querySelector('#searchForm');
        const dateInput = document.querySelector('#date');
        const appointmentsDiv = document.querySelector('#appointments');

        let appointments = [];
        let ulElement = null;
        let loading = true;
        const loadingElement = document.querySelector('#loading');
        handleLoading();

        const form = new window.App.Validator({
            date: [
                dateInput.value, 
                [
                    [window.App.ValidationFunctions.required]
                ]
            ]
        }, true);

        getAppointments(dateInput.value);

        searchForm.onsubmit = (e) => {
            e.preventDefault();
            form.markAllAsTouched();

            appointments = [];
            if(ulElement != null) ulElement.remove();
            loading = true;
            handleLoading();
            getAppointments(dateInput.value);
        }

        function getAppointments(date){
            window.App.services.getAppointments(dateInput.value)
            .then( (res) => {
                loading = false;
                handleLoading();
                (res != undefined)? appointments = res: appointments = [];

                ulElement = handleAppointments(appointmentsDiv, appointments);
            })
            .catch((e) => {
                loading = false;
                handleLoading();
                console.log(`Can't load resources ${e}`);
            });
        }

        function handleLoading() {
            if(loading) {
                loadingElement.classList.remove('hidden');
                loadingElement.classList.add('block');
                return;
            };

            loadingElement.classList.add('hidden');
            loadingElement.classList.remove('block');
        }

        function handleAppointments(appointmentsDiv, appointments) {

            const ul = document.createElement('ul');
            ul.classList.add('appointments-list');
            console.log(appointments.length);

            if(appointments.length === 0) {
                const nothingElement = document.createElement('p');
                nothingElement.textContent = "No hay elementos para mostrar";
                ul.appendChild(nothingElement);
                appointmentsDiv.appendChild(ul);
                return ul;
            }

            appointments.forEach((a, index) => {
                const li = document.createElement('li');
                ul.appendChild(li);

                const totalPrice = a.services.map((s) => s.price).reduce((accumulator, currValue) => accumulator + currValue);;

                li.innerHTML = `
                    <div class="flex justify-between items-center">
                        <div class="flex-1">
                            <h4>${a.clientName}</h4>
                            <h5>${a.hour}</h5>
                        </div>
                        <div id="options-${index}" class="cursor-pointer">
                            <button id="toggle" class="bg-neutral-800 rounded-full"></button>
                            <div class="toolbox right-0 bg-neutral-800 rounded-sm p-1.5 h-16 overflow-y-auto">
                                <button class="cursor-pointer" id="pay">Cobrar</button>
                                <button class="cursor-pointer" id="cancel">Cancelar</button>
                            </div>
                        </div>
                    </div>

                    <div class="services">
                        ${a.services.map((s) => `
                            <div>
                                <p>${s.name}</p>
                            </div>
                        `).join(' ')}
                    </div>

                    <p class="text-lg text-green-500">Total a pagar: $${totalPrice}</p>
                `;
            });

            appointmentsDiv.appendChild(ul);

            appointments.forEach((a, i) => {
                const toolbox = new window.App.Menu(`#options-${i}`);

                toolbox.getToolboxChildren(0).onclick = () => {
                    console.log('cobrar');
                };
                toolbox.getToolboxChildren(1).onclick = () => {
                    console.log('cancelar');
                };
            });

            return ul;
        }
    })
</script>