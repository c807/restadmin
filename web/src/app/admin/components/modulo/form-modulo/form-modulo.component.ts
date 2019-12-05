import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';
import { ModuloService } from '../../../services/modulo.service';
import { Modulo } from '../../../models/modulo';

@Component({
  selector: 'app-form-modulo',
  templateUrl: './form-modulo.component.html',
  styleUrls: ['./form-modulo.component.css']
})
export class FormModuloComponent implements OnInit {

  @Input() public modulo: Modulo;
  @Output() public saved = new EventEmitter();

  constructor(
    private moduloSrvc: ModuloService
  ) { }

  ngOnInit() {
  }

  public resetModulo() {
    this.modulo = new Modulo(null, null, 0);
  }

  public onSubmit() {
    this.moduloSrvc.save(this.modulo).subscribe((res) => {
      if (res) {
        this.saved.emit();
        this.resetModulo();
      }
    });
  }

}
