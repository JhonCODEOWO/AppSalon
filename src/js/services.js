import Swal from "sweetalert2";
import { apiUrl } from "./app";

export class Service {
    constructor(id, name, price, createdBy){
        this.id = id ?? null;
        this.name = name ?? null;
        this.price = price ?? null;
        this.createdBy = createdBy ?? null;
    }
}


export async function getServices() {
    try {
        const url = `${apiUrl}/services`;
        const result = await fetch(url);

        const services = await result.json();
        
        return toServiceArray(services);
    } catch (error) {
        console.log(error);
    }
}

export async function storeService(formData){
    const url = `${apiUrl}/services`;

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                accept: "application/json"
            },
            body: formData,
        });

        const result = await response.json();

        return result;
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Ooops! Something went wrong.',
            text: "It seems your request can't be completed now, try again later."
        });
    }
}

export function toServiceArray(arrayJsonData){
    let services = [];
    arrayJsonData.forEach(element => {
        const service = new Service(element['id'], element['name'], element['price'], element['createdBy']);

        services = [...services, service];
    });

    return services;
}