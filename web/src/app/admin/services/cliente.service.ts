import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { GLOBAL } from '../../shared/global';
import { ServiceErrorHandler } from '../../shared/error-handler';
import { Cliente } from '../models/cliente';
import { LocalstorageService } from '../services/localstorage.service';
import { Observable } from 'rxjs';
import { retry, catchError } from 'rxjs/operators';
import * as qs from 'qs';

@Injectable({
  providedIn: 'root'
})
export class ClienteService {

  private srvcErrHndl: ServiceErrorHandler;
  private moduleUrl: string = 'cliente';
  private usrToken: string = null;

  constructor(
    private http: HttpClient,
    private ls: LocalstorageService
  ) {
    this.srvcErrHndl = new ServiceErrorHandler();
    this.usrToken = this.ls.get(GLOBAL.usrTokenVar) ? this.ls.get(GLOBAL.usrTokenVar).token : null;
  }

  getAll(): Observable<Cliente> {
    GLOBAL.sharedHeaders['Authorization'] = this.usrToken;
    const httpOptions = {
      headers: new HttpHeaders(GLOBAL.sharedHeaders)
    };    
    return this.http.get<Cliente>(`${GLOBAL.url}/${this.moduleUrl}/clientes.json`, httpOptions).pipe(retry(1), catchError(this.srvcErrHndl.errorHandler));
  }

  get(fltr: any): Observable<Cliente> {
    GLOBAL.sharedHeaders['Authorization'] = this.usrToken;
    const httpOptions = {
      headers: new HttpHeaders(GLOBAL.sharedHeaders)
    };    
    return this.http.get<Cliente>(`${GLOBAL.url}/${this.moduleUrl}/cliente.json?${qs.stringify(fltr)}`, httpOptions).pipe(retry(1), catchError(this.srvcErrHndl.errorHandler));
  }

  save(entidad: Cliente): Observable<Cliente> {
    GLOBAL.sharedHeaders['Authorization'] = this.usrToken;
    const httpOptions = {
      headers: new HttpHeaders(GLOBAL.sharedHeaders)
    };

    if(entidad.cliente){
      return this.http.put<Cliente>(`${GLOBAL.url}/${this.moduleUrl}/cliente.json?cliente=${entidad.cliente}`, entidad, httpOptions).pipe(retry(1), catchError(this.srvcErrHndl.errorHandler));
    } else {
      delete entidad.cliente;
      return this.http.post<Cliente>(`${GLOBAL.url}/${this.moduleUrl}/cliente.json`, entidad, httpOptions).pipe(retry(1), catchError(this.srvcErrHndl.errorHandler));
    }
  } 

}
