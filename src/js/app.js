import { getServices } from "./services";
import { steps } from "./steps";

export const apiUrl = "http://localhost:8000/api";

export function app(){
    steps();
}