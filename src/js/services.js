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

export function toServiceArray(arrayJsonData){
    let services = [];
    arrayJsonData.forEach(element => {
        const service = new Service(element['id'], element['name'], element['price'], element['createdBy']);

        services = [...services, service];
    });

    return services;
}