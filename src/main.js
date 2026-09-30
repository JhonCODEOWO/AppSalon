import { apiUrl, app } from "./js/app"
import { getServices } from "./js/services";
import { required, Validator } from "./js/validator";
import "./style.css"

document.addEventListener('DOMContentLoaded', () => {
    app();
})

window.App ??= {};

window.App.urlApi = apiUrl;
window.App.services = {
    getServices: getServices
}
window.App.Validator = Validator;
window.App.ValidationFunctions = {};
window.App.ValidationFunctions.required = required;