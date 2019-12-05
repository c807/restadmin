import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { GLOBAL } from '../../shared/global';
import { ServiceErrorHandler } from '../../shared/error-handler';
import { ClienteCorporacion } from '../models/cliente-corporacion';
import { LocalstorageService } from '../services/localstorage.service';
import { Observable } from 'rxjs';
import { retry, catchError } from 'rxjs/operators';
import * as qs from 'qs';

@Injectable({
  providedIn: 'root'
})
export class ClienteCorporacionService {

  private srvcErrHndl: ServiceErrorHandler;
  private moduleUrl: string = 'cliente_corporacion';
  private usrToken: string = null;

  constructor(
    private http: HttpClient,
    private ls: LocalstorageService
  ) {
    this.srvcErrHndl = new ServiceErrorHandler();
    this.usrToken = this.ls.get(GLOBAL.usrTokenVar) ? this.ls.get(GLOBAL.usrTokenVar).token : null;
  }

  getAll(): Observable<ClienteCorporacion[]> {
    GLOBAL.sharedHeaders['Authorization'] = this.usrToken;
    const httpOptions = {
      headers: new HttpHeaders(GLOBAL.sharedHeaders)
    };    
    return this.http.get<ClienteCorporacion[]>(`${GLOBAL.url}/${this.moduleUrl}/cliente_corporaciones.json`, httpOptions).pipe(retry(1), catchError(this.srvcErrHndl.errorHandler));
  }

  get(fltr: any): Observable<ClienteCorporacion[]> {
    GLOBAL.sharedHeaders['Authorization'] = this.usrToken;
    const httpOptions = {
      headers: new HttpHeaders(GLOBAL.sharedHeaders)
    };    
    return this.http.get<ClienteCorporacion[]>(`${GLOBAL.url}/${this.moduleUrl}/cliente_corporacion.json?${qs.stringify(fltr)}`, httpOptions).pipe(retry(1), catchError(this.srvcErrHndl.errorHandler));
  }

  save(entidad: ClienteCorporacion): Observable<ClienteCorporacion> {
    GLOBAL.sharedHeaders['Authorization'] = this.usrToken;
    const httpOptions = {
      headers: new HttpHeaders(GLOBAL.sharedHeaders)
    };

    const obj: any = entidad;
    obj.cliente = entidad.cliente.cliente;

    if(entidad.cliente_corporacion){
      return this.http.put<ClienteCorporacion>(`${GLOBAL.url}/${this.moduleUrl}/cliente_corporacion.json?cliente_corporacion=${entidad.cliente_corporacion}`, obj, httpOptions).pipe(retry(1), catchError(this.srvcErrHndl.errorHandler));
    } else {
      delete entidad.cliente_corporacion;
      return this.http.post<ClienteCorporacion>(`${GLOBAL.url}/${this.moduleUrl}/cliente_corporacion.json`, obj, httpOptions).pipe(retry(1), catchError(this.srvcErrHndl.errorHandler));
    }
  }  
}
