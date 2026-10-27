<h1>Panel de administración</h1>
<h2>Bienvenido <?php echo Auth()::user()->name ?>  </h1>

<a href="/createAppointment">Crear cita</a>

<div>
    <form id="searchForm" class="flex justify-between gap-x-2">
        <fieldset class="input-fieldset">
            <label for="date">Fecha</label>
            <input type="date" name="date" id="date">
        </fieldset>
        <input type="submit" value="Buscar...">
    </form>
</div>

<h3>Citas</h3>
<div id="appointments">

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const divAppointments = document.querySelector('#appointments');
        const searchForm = document.querySelector('#searchForm');
        const form = new window.App.Validator({
            date: [
                null, 
                [
                    [window.App.ValidationFunctions.required]
                ]
            ]
        }, true);

        console.log(searchForm);
        searchForm.onsubmit = (e) => {
            e.preventDefault();
            form.markAllAsTouched();

            console.log(form.invalid());
        }
    })
</script>