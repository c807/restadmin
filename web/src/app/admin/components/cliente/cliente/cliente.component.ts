import { Component, OnInit, ViewChild } from '@angular/core';
import { ListaComponent } from '../lista/lista.component';
import { Cliente } from '../../../models/cliente';

@Component({
  selector: 'app-cliente',
  templateUrl: './cliente.component.html',
  styleUrls: ['./cliente.component.css']
})
export class ClienteComponent implements OnInit {

  public cliente: Cliente;
  @ViewChild('lstClientesComponent', { static: false }) lstClientesComponent: ListaComponent;

  constructor() { }

  ngOnInit() {
    this.cliente = new Cliente(null, null, null, null, null, 0);
  }

  setCliente(obj: Cliente){
    this.cliente = obj;
  }

  refreshList() {
    this.lstClientesComponent.loadClientes();
  }

}
