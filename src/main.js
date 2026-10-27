import { apiUrl, app } from "./js/app"
import { Menu } from "./js/menu";
import { getServices, storeService } from "./js/services";
import { required, Validator } from "./js/validator";
import "./style.css";
import Swal from "sweetalert2";

document.addEventListener('DOMContentLoaded', () => {
    app();
})

window.App ??= {};
window.App.Swal = Swal;
window.App.urlApi = apiUrl;
window.App.services = {
    getServices: getServices,
    storeService: storeService
}
window.App.Menu = Menu;
window.App.Validator = Validator;
window.App.ValidationFunctions = {};
window.App.ValidationFunctions.required = required;