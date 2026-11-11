<h1>Servicios</h1>

<a href="/services/create" class="button button-success mb-3">Crear servicio</a>

<div id="servicesContainer">

</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const servicesContainer = document.querySelector("#servicesContainer");
        let serviceRecords = [];
        let ulServicesElement = null; 

        loadServices();

        function loadServices() {
            window.App.services.getServices()
            .then((res) => {
                if(res === undefined) return;
                serviceRecords = res;
                ulServicesElement = renderServices(servicesContainer, serviceRecords);
            })
            .catch((e) => {
                console.log(e);
            })
        }

        function renderServices(container, services) {
            const ulElement = document.createElement('ul');
            ulElement.id = "ul-services";
            ulElement.classList.add("services");
            const menuNames = [];

            services.forEach((s) => {
                const menuName = `menu-${s.id}`;
                const li = document.createElement('li');
                li.classList.add("service");
                li.innerHTML = `
                    <p class="service__name">${s.name}</p>
                    <p class="service__price">${s.price}</p>
                    <div id=${menuName}>
                        <div id="toggle"></div>
                        <div class="toolbox right-0 bg-neutral-900 p-4 rounded z-50 flex flex-col gap-y-2">
                            <a href="/services/edit/${s.id}">Editar</a>
                            <button class="delete-service" data-id="${s.id}">Eliminar</button>
                        </div>
                    </div>
                `;

                menuNames.push(`#${menuName}`);
                ulElement.appendChild(li);
            })

            container.appendChild(ulElement);
            menuNames.forEach(menuName => {
                const menu = new window.App.Menu(menuName);
            })

            const deleteButtons = document.querySelectorAll('#ul-services .delete-service');

            addEventListeners(deleteButtons);

            return ulElement;
        }

        function addEventListeners(nodeList) {
            nodeList.forEach((button) => {
                button.addEventListener('click', (e) => {
                    const curr = e.currentTarget;
                    window.App.Swal.fire({
                        icon: 'warning',
                        title: 'Inhabilitar servicio',
                        text: 'Vas a inhabilitar el servicio, al hacerlo tus clientes no podrán visualizarlo en el momento de agendar una cita.',
                        showDenyButton: true,
                        denyButtonText: "Cancelar",
                        confirmButtonText: "Continuar"
                    }).then((result) => {
                        const {isDeny, isConfirmed, isDismissed} = result;

                        if(isDeny || isDismissed) return;

                        console.log(curr.dataset.id);
                    });
                })
            })
        }
    })
</script>