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

export async function appointments(date){
    try {
        console.log(date);
        const url = `${apiUrl}/appointments?dateToSearch=${date}`;
        const response = await fetch(url, {
            method: 'GET',
        })

        if(!response.ok) throw new Error(`Can't proceed the request, try again.`);

        const data = await response.json();

        return data;
    } catch (error) {
        console.log(error);
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

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                accept: "application/json"
            },
            body: formData,
        });

        if(!response.ok) throw new Error(`Can't make the request: ${response.status}`);

        const result = await response.json();

        return result;
}

export function toServiceArray(arrayJsonData){
    let services = [];
    arrayJsonData.forEach(element => {
        const service = new Service(element['id'], element['name'], element['price'], element['createdBy']);

        services = [...services, service];
    });

    return services;
}