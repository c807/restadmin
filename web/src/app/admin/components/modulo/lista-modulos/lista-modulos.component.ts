import { Component, OnInit, Output, EventEmitter } from '@angular/core';
import { ModuloService } from '../../../services/modulo.service';
import { Modulo } from '../../../models/modulo';

@Component({
  selector: 'app-lista-modulos',
  templateUrl: './lista-modulos.component.html',
  styleUrls: ['./lista-modulos.component.css']
})
export class ListaModulosComponent implements OnInit {

  @Output() public getModuloEv = new EventEmitter();
  public lstModulos: Modulo[] = [];
  public modulo: Modulo;

  constructor(
    private moduloSrvc: ModuloService
  ) { }

  ngOnInit() {
    this.modulo = new Modulo(null, null, 0);
    this.loadModulos();
  }

  loadModulos() {
    this.moduloSrvc.getAll().subscribe((res) => {
      if (res) {
        this.lstModulos = res;
      }
    });
  }

  getModulo(id: number){
    this.moduloSrvc.get({ modulo: id }).subscribe((res) => {
      if (res) {
        if (res.length > 0){
          this.modulo = res[0];
          this.getModuloEv.emit(this.modulo);
        }
      }
    });
  }

}
