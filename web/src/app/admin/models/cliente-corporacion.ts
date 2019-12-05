import { Cliente } from './cliente';

export class ClienteCorporacion {
    constructor(
        public cliente_corporacion: number,
        public cliente: Cliente,
        public llave: string,
        public nombre: string,
        public descripcion: string,
        public debaja: number        
    ){}
}
