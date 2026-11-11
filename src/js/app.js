import { getServices } from "./services";
import { steps } from "./steps";
export const apiUrl = `${import.meta.env.VITE_APP_HOST}/api`;

export function app(){
    steps();
}