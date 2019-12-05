import { Component, OnInit, Input, Output, EventEmitter, OnChanges, SimpleChanges } from '@angular/core';
import { MatTableDataSource } from '@angular/material/table';
import { ClienteService } from '../../../services/cliente.service';
import { ClienteCorporacionService } from '../../../services/cliente-corporacion.service';
import { EntidadFacturacionService } from '../../../services/entidad-facturacion.service';
import { ModuloService } from '../../../services/modulo.service';
import { ClienteCorporacionModuloService } from '../../../services/cliente-corporacion-modulo.service';
import { Cliente } from '../../../models/cliente';
import { ClienteCorporacion } from '../../../models/cliente-corporacion';
import { EntidadFacturacion } from '../../../models/entidad-facturacion';
import { Modulo } from '../../../models/modulo';
import { ClienteCorporacionModulo } from '../../../models/cliente-corporacion-modulo';
import { MatSnackBar } from '@angular/material/snack-bar';

declare var gapi: any;

@Component({
  selector: 'app-form-cliente',
  templateUrl: './form-cliente.component.html',
  styleUrls: ['./form-cliente.component.css']
})
export class FormClienteComponent implements OnInit, OnChanges {

  @Input() public cliente: Cliente;
  @Output() public saved = new EventEmitter();

  public corporaciones: ClienteCorporacion[];
  public corporacion: ClienteCorporacion;
  public entsFactura: EntidadFacturacion[];
  public entFactura: EntidadFacturacion;
  public modulos: Modulo[];
  public modulosCorporacion: ClienteCorporacionModulo[];
  public moduloCorporacion: ClienteCorporacionModulo;
  public dcTblClienteCorporacion: string[] = ['ver', 'nombre', 'llave', 'descripcion', 'autenticar', 'crearInstancia'];
  public dsTblClienteCorporacion: MatTableDataSource<ClienteCorporacion>;
  public dcTblEntidadFacturacion: string[] = ['ver', 'nombre', 'nit'];
  public dsTblEntidadFacturacion: MatTableDataSource<EntidadFacturacion>;
  public dcTblModulosCorporacion: string[] = ['modulo'];
  public dsTblModulosCorporacion: MatTableDataSource<ClienteCorporacionModulo>;

  public edCorporacion = false;
  public edEntidad = false;
  public edModulo = false;

  public autenticado = false;

  private gcpProject = 'spc-c807-261121';
  private gcpSQLInstance = 'spcrtvsv04';
  private gcpStorage = 'gs://rtstorage/rtdbtest_02.sql';

  constructor(
    private clienteSrvc: ClienteService,
    private clienteCorporacionSrvc: ClienteCorporacionService,
    private entidadFacturacionSrvc: EntidadFacturacionService,
    private moduloSrvc: ModuloService,
    private cliCorpModSrvc: ClienteCorporacionModuloService,
    private _snackBar: MatSnackBar
  ) {
    this.resetCliente();
  }

  private resetCliente() {
    this.cliente = new Cliente(null, null, null, null, null, 0);
    this.resetCorporacionCliente();
    this.corporaciones = [];
  }

  private resetCorporacionCliente() {
    this.corporacion = new ClienteCorporacion(null, this.cliente, null, null, null, 0);
    this.resetEntidadFacturacion();
    this.resetModuloCorporacion();
    this.entsFactura = [];
    this.modulosCorporacion = [];
  }

  private resetEntidadFacturacion() {
    this.entFactura = new EntidadFacturacion(null, this.corporacion, null, null, null, null, 0);
  }

  private resetModuloCorporacion() {
    this.moduloCorporacion = new ClienteCorporacionModulo(null, null);
  }

  ngOnInit() {
    gapi.load("client:auth2", function () {
      gapi.auth2.init({ client_id: "228355871807-a6k02rcf0ppeluj81sntgp4lhr7r61ca.apps.googleusercontent.com" });
    });
  }

  ngOnChanges(changes: SimpleChanges) {
    if (changes !== undefined && changes !== null) {
      if (changes.cliente) {
        if (changes.cliente.currentValue && changes.cliente.previousValue) {
          if ((+changes.cliente.currentValue.cliente !== +changes.cliente.previousValue.cliente) && (+changes.cliente.currentValue.cliente > 0)) {
            this.corporacion.cliente = changes.cliente.currentValue;
            this.resetCorporacionCliente();
            this.loadCorporaciones();
          }
        }
      }
    }
  }

  onSubmit() {
    this.clienteSrvc.save(this.cliente).subscribe((res) => {
      if (res) {
        this.cliente = res;
        this.saved.emit();
      }
    });
  }

  loadCorporaciones() {
    this.clienteCorporacionSrvc.get({ cliente: this.cliente.cliente }).subscribe((res) => {
      if (res) {
        this.corporaciones = res;
        this.dsTblClienteCorporacion = new MatTableDataSource(this.corporaciones);
      }
    });
  }

  onSubmitCorporacion() {
    this.corporacion.cliente = this.cliente;
    this.clienteCorporacionSrvc.save(this.corporacion).subscribe((res) => {
      if (res) {
        this.loadCorporaciones();
        this.resetCorporacionCliente();
      }
    });
  }

  getClienteCorporacion(row: ClienteCorporacion) {
    this.clienteCorporacionSrvc.get({ cliente_corporacion: row.cliente_corporacion }).subscribe((res) => {
      if (res) {
        if (res.length > 0) {
          this.corporacion = res[0];
          this.edCorporacion = true;
          this.loadEntidadesFacturacion();
          this.loadModulos();
          this.loadCorporacionModulos();
        }
      }
    });
  }

  loadEntidadesFacturacion() {
    this.entidadFacturacionSrvc.get({ cliente_corporacion: this.corporacion.cliente_corporacion }).subscribe((res) => {
      if (res) {
        if (res.length > 0) {
          this.entsFactura = res;
          this.dsTblEntidadFacturacion = new MatTableDataSource(this.entsFactura);
        }
      }
    });
  }

  getEntidadFacturacion(row: EntidadFacturacion) {
    this.entidadFacturacionSrvc.get({ entidad_facturacion: row.entidad_facturacion }).subscribe((res) => {
      if (res) {
        if (res.length > 0) {
          this.entFactura = res[0];
          this.edEntidad = true;
        }
      }
    });
  }

  onSubmitEntidadFactura() {
    this.entFactura.cliente_corporacion = this.corporacion;
    this.entidadFacturacionSrvc.save(this.entFactura).subscribe((res) => {
      if (res) {
        this.loadEntidadesFacturacion();
        this.resetEntidadFacturacion();
        this.edEntidad = false;
      }
    });
  }

  loadModulos() {
    this.moduloSrvc.get({ debaja: 0 }).subscribe((res) => {
      if (res) {
        this.modulos = res;
      }
    });
  }

  loadCorporacionModulos() {
    this.cliCorpModSrvc.get({ cliente_corporacion: this.corporacion.cliente_corporacion }).subscribe((res) => {
      if (res) {
        if (res.length > 0) {
          this.modulosCorporacion = res;
          this.dsTblModulosCorporacion = new MatTableDataSource(this.modulosCorporacion);
        }
      }
    });
  }

  onSubmitModuloCorporacion() {
    this.moduloCorporacion.cliente_corporacion = this.corporacion.cliente_corporacion;
    this.cliCorpModSrvc.save(this.moduloCorporacion).then((res) => {
      this.loadCorporacionModulos();
      this.resetModuloCorporacion();
      this.edModulo = false;
    });
  }

  authenticate() {
    return gapi.auth2.getAuthInstance()
      .signIn({ scope: "https://www.googleapis.com/auth/cloud-platform https://www.googleapis.com/auth/sqlservice.admin" })
      .then(function () { console.log("Sign-in successful"); },
        function (err) { console.error("Error signing in", err); });
  }

  loadClient() {
    gapi.client.setApiKey("AIzaSyD3VUglnpLKjBYZ44EXitMaPnx1Z4QRLE8");
    return gapi.client.load("https://content.googleapis.com/discovery/v1/apis/sqladmin/v1beta4/rest")
      .then(function () { console.log("GAPI client loaded for API"); },
        function (err) { console.error("Error loading GAPI client for API", err); });
  }

  async delay(ms: number) {
    await new Promise(resolve => setTimeout(() => resolve(), ms)).then(() => { });
  }

  waitUntilCreated(operacion: string) {
    let snckBar;
    return gapi.client.sql.operations.get({
      "project": this.gcpProject,
      "operation": operacion
    }).then(
      (response: any) => {
        //console.log("Response", response.result);
        const resultado = response.result;
        if (resultado.status !== 'DONE') {
          snckBar = this._snackBar.open('En proceso...', 'Creación de Instancia de MySQL', { duration: 5000 });
          this.delay(30000).then(() => this.waitUntilCreated(operacion));
        } else {
          snckBar = this._snackBar.open('Terminada...', 'Creación de Instancia de MySQL', { duration: 5000 });
          this.createDatabase();
        }
      },
      (err: any) => {
        console.error("Execute error", err);
        snckBar = this._snackBar.open('ERROR!!!', 'Creación de Instancia de MySQL', { duration: 30000 });
      });
  }

  execute() {
    return gapi.client.sql.instances.insert({
      "project": this.gcpProject,
      "resource": {
        "databaseVersion": "MYSQL_5_7",
        "settings": {
          "dataDiskType": "PD_SSD",
          "tier": "db-g1-small",
          "activationPolicy": "ALWAYS",
          "pricingPlan": "PER_USE",
          "ipConfiguration": {
            "authorizedNetworks": [
              {
                "name": "C807I1",
                "value": "138.117.143.26"
              },
              {
                "name": "C807I2",
                "value": "190.99.118.210"
              }
            ],
            "requireSsl": false
          }
        },
        "name": this.gcpSQLInstance,
        "region": "us-central1",
        "rootPassword": "HcjZgm_q5mA8&G&-YgtqWQu72g=B2pd6wQ6J^4S#$eh$fBA3MBr+n-SrWaGXAKM&+=@RJXzP@ubLw%9japB4Cg2snq9%_+U#CQw@kZ5qe32&LFMq&pYCvJqMpSKavaAH"
      }
    }).then(
      (response: any) => {
        //console.log("Response", response.result);
        const resultado = response.result;
        if (resultado.status === 'PENDING') {
          this.waitUntilCreated(resultado.name);
        }
      },
      (err: any) => {
        console.error("Execute error", err);
      });
  }

  waitUntilDBCreated(operacion: string) {
    let snckBar;
    return gapi.client.sql.operations.get({
      "project": this.gcpProject,
      "operation": operacion
    }).then(
      (response: any) => {
        //console.log("Response", response.result);
        const resultado = response.result;
        if (resultado.status !== 'DONE') {
          snckBar = this._snackBar.open('En proceso...', 'Creación de BD', { duration: 5000 });
          this.delay(30000).then(() => this.waitUntilDBCreated(operacion));
        } else {
          snckBar = this._snackBar.open('Terminada...', 'Creación de BD', { duration: 5000 });
          this.importDataAndStructure();
        }
      },
      (err: any) => {
        console.error("Execute error", err);
        snckBar = this._snackBar.open('ERROR!!!', 'Creación de BD de MySQL', { duration: 30000 });
      });
  }

  createDatabase() {
    let snckBar;
    return gapi.client.sql.databases.insert({
      "project": this.gcpProject,
      "instance": this.gcpSQLInstance,
      "resource": {
        "name": this.gcpSQLInstance,
        "instance": this.gcpSQLInstance,
        "project": this.gcpProject
      }
    }).then(
      (response: any) => {
        //console.log("Response", response.result);
        const resultado = response.result;
        this.waitUntilDBCreated(resultado.name);
        /*
        if (resultado.status === 'PENDING') {
          this.waitUntilDBCreated(resultado.name);
        }
        snckBar = this._snackBar.open('En proceso...', 'Creación de BD', { duration: 5000 });
        */
      },
      (err: any) => {
        console.error("Execute error", err);
      });
  }

  waitImportDBAndSt(operacion: string) {
    let snckBar;
    return gapi.client.sql.operations.get({
      "project": this.gcpProject,
      "operation": operacion
    }).then(
      (response: any) => {
        //console.log("Response", response.result);
        const resultado = response.result;
        if (resultado.status !== 'DONE') {
          snckBar = this._snackBar.open('En proceso...', 'Importación de datos', { duration: 5000 });
          this.delay(30000).then(() => this.waitImportDBAndSt(operacion));
        } else {
          snckBar = this._snackBar.open('Terminada...', 'Importación de datos', { duration: 5000 });
        }
      },
      (err: any) => {
        console.error("Execute error", err);
        snckBar = this._snackBar.open('ERROR!!!', 'Importación de datos', { duration: 30000 });
      });
  }

  importDataAndStructure() {
    let snckBar;
    return gapi.client.sql.instances.import({
      "project": this.gcpProject,
      "instance": this.gcpSQLInstance,
      "resource": {
        "importContext": {
          "database": this.gcpSQLInstance,
          "uri": this.gcpStorage,
          "fileType": "SQL"
        }
      }
    }).then((response: any) => {
      console.log("Response", response.result);
      const resultado = response.result;
      if (resultado.status === 'PENDING') {
        this.waitImportDBAndSt(resultado.name);
      }
      snckBar = this._snackBar.open('En proceso...', 'Creación de BD', { duration: 5000 });
    },
      (err: any) => { console.error("Execute error", err); });
  }

  autenticar() {
    this.authenticate().then(this.loadClient().then(this.autenticado = true));
  }

  crearInstancia(element: ClienteCorporacion) {
    this.execute();
  }
}
