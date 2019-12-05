import { ClienteCorporacion } from './cliente-corporacion';

export class EntidadFacturacion {
    constructor(
        public entidad_facturacion: number,
        public cliente_corporacion: ClienteCorporacion,
        public nombre: string,
        public nit: string,
        public direccion: string,
        public correoe: string,
        public debaja: number
    ) { }
}
