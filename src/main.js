import { apiUrl, app } from "./js/app"
import { getServices } from "./js/services";
import "./style.css"

document.addEventListener('DOMContentLoaded', () => {
    app();
})

window.App ??= {};

window.App.urlApi = apiUrl;
window.App.services = {
    getServices: getServices
}