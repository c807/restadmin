import { Component, OnInit, Output, EventEmitter } from '@angular/core';
import { Cliente } from '../../../models/cliente';
import { ClienteService } from '../../../services/cliente.service';

@Component({
  selector: 'app-lista',
  templateUrl: './lista.component.html',
  styleUrls: ['./lista.component.css']
})
export class ListaComponent implements OnInit {

  listaClientes: Cliente;
  @Output() getClienteEv = new EventEmitter();

  constructor(
    private clienteSrvc: ClienteService
  ) { }

  ngOnInit() {
    this.loadClientes();
  }

  loadClientes() {
    this.clienteSrvc.getAll().subscribe((lst) => {
      if (lst) {
        this.listaClientes = lst;
      }
    });
  }

  getCliente(id: number) {
    const fltr = { cliente: id };
    this.clienteSrvc.get(fltr).subscribe((lst: any) => {
      if (lst) {
        if (lst.length > 0)
          this.getClienteEv.emit(lst[0]);
      }
    });
  }
}
