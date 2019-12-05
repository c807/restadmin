import { Component, OnInit, ViewChild } from '@angular/core';
import { ListaModulosComponent } from '../lista-modulos/lista-modulos.component';
import { Modulo } from '../../../models/modulo';

@Component({
  selector: 'app-modulo',
  templateUrl: './modulo.component.html',
  styleUrls: ['./modulo.component.css']
})
export class ModuloComponent implements OnInit {

  public modulo: Modulo;
  @ViewChild('lstModulos', { static: false }) lstModulos: ListaModulosComponent;

  constructor() {
    this.modulo = new Modulo(null, null, 0);
  }

  ngOnInit() {
  }

  setModulo(ev: Modulo){
    this.modulo = ev;
  }

  refreshList(){
    this.lstModulos.loadModulos();
  }

}
