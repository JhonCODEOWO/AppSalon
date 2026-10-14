import { apiUrl, app } from "./js/app"
import { getServices, storeService } from "./js/services";
import { required, Validator } from "./js/validator";
import "./style.css"

document.addEventListener('DOMContentLoaded', () => {
    app();
})

window.App ??= {};

window.App.urlApi = apiUrl;
window.App.services = {
    getServices: getServices,
    storeService: storeService
}
window.App.Validator = Validator;
window.App.ValidationFunctions = {};
window.App.ValidationFunctions.required = required;