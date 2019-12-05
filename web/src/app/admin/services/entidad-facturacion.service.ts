import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { GLOBAL } from '../../shared/global';
import { ServiceErrorHandler } from '../../shared/error-handler';
import { EntidadFacturacion } from '../models/entidad-facturacion';
import { LocalstorageService } from '../services/localstorage.service';
import { Observable } from 'rxjs';
import { retry, catchError } from 'rxjs/operators';
import * as qs from 'qs';

@Injectable({
  providedIn: 'root'
})
export class EntidadFacturacionService {

  private srvcErrHndl: ServiceErrorHandler;
  private moduleUrl: string = 'entidad_facturacion';
  private usrToken: string = null;

  constructor(
    private http: HttpClient,
    private ls: LocalstorageService
  ) {
    this.srvcErrHndl = new ServiceErrorHandler();
    this.usrToken = this.ls.get(GLOBAL.usrTokenVar) ? this.ls.get(GLOBAL.usrTokenVar).token : null;
  }

  getAll(): Observable<EntidadFacturacion[]> {
    GLOBAL.sharedHeaders['Authorization'] = this.usrToken;
    const httpOptions = {
      headers: new HttpHeaders(GLOBAL.sharedHeaders)
    };    
    return this.http.get<EntidadFacturacion[]>(`${GLOBAL.url}/${this.moduleUrl}/entidades_facturacion.json`, httpOptions).pipe(retry(1), catchError(this.srvcErrHndl.errorHandler));
  }

  get(fltr: any): Observable<EntidadFacturacion[]> {
    GLOBAL.sharedHeaders['Authorization'] = this.usrToken;
    const httpOptions = {
      headers: new HttpHeaders(GLOBAL.sharedHeaders)
    };    
    return this.http.get<EntidadFacturacion[]>(`${GLOBAL.url}/${this.moduleUrl}/entidad_facturacion.json?${qs.stringify(fltr)}`, httpOptions).pipe(retry(1), catchError(this.srvcErrHndl.errorHandler));
  }

  save(entidad: EntidadFacturacion): Observable<EntidadFacturacion> {
    GLOBAL.sharedHeaders['Authorization'] = this.usrToken;
    const httpOptions = {
      headers: new HttpHeaders(GLOBAL.sharedHeaders)
    };

    const obj: any = entidad;
    obj.cliente_corporacion = entidad.cliente_corporacion.cliente_corporacion;

    if(entidad.entidad_facturacion){
      return this.http.put<EntidadFacturacion>(`${GLOBAL.url}/${this.moduleUrl}/entidad_facturacion.json?entidad_facturacion=${entidad.entidad_facturacion}`, obj, httpOptions).pipe(retry(1), catchError(this.srvcErrHndl.errorHandler));
    } else {
      delete entidad.entidad_facturacion;
      return this.http.post<EntidadFacturacion>(`${GLOBAL.url}/${this.moduleUrl}/entidad_facturacion.json`, obj, httpOptions).pipe(retry(1), catchError(this.srvcErrHndl.errorHandler));
    }
  }  
}
