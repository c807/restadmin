import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { GLOBAL } from '../../shared/global';
import { ServiceErrorHandler } from '../../shared/error-handler';
import { ClienteCorporacionModulo } from '../models/cliente-corporacion-modulo';
import { LocalstorageService } from '../services/localstorage.service';
import { Observable } from 'rxjs';
import { retry, catchError } from 'rxjs/operators';
import * as qs from 'qs';

@Injectable({
  providedIn: 'root'
})
export class ClienteCorporacionModuloService {

  private srvcErrHndl: ServiceErrorHandler;
  private moduleUrl: string = 'cliente_corporacion_modulo';
  private usrToken: string = null;

  constructor(
    private http: HttpClient,
    private ls: LocalstorageService
  ) {
    this.srvcErrHndl = new ServiceErrorHandler();
    this.usrToken = this.ls.get(GLOBAL.usrTokenVar) ? this.ls.get(GLOBAL.usrTokenVar).token : null;
  }

  getAll(): Observable<ClienteCorporacionModulo[]> {
    GLOBAL.sharedHeaders['Authorization'] = this.usrToken;
    const httpOptions = {
      headers: new HttpHeaders(GLOBAL.sharedHeaders)
    };    
    return this.http.get<ClienteCorporacionModulo[]>(`${GLOBAL.url}/${this.moduleUrl}/cliente_corporacion_modulos.json`, httpOptions).pipe(retry(1), catchError(this.srvcErrHndl.errorHandler));
  }

  get(fltr: any): Observable<ClienteCorporacionModulo[]> {
    GLOBAL.sharedHeaders['Authorization'] = this.usrToken;
    const httpOptions = {
      headers: new HttpHeaders(GLOBAL.sharedHeaders)
    };    
    return this.http.get<ClienteCorporacionModulo[]>(`${GLOBAL.url}/${this.moduleUrl}/cliente_corporacion_modulo.json?${qs.stringify(fltr)}`, httpOptions).pipe(retry(1), catchError(this.srvcErrHndl.errorHandler));
  }

  async save(entidad: ClienteCorporacionModulo): Promise<ClienteCorporacionModulo> {
    GLOBAL.sharedHeaders['Authorization'] = this.usrToken;
    const httpOptions = {
      headers: new HttpHeaders(GLOBAL.sharedHeaders)
    };

    const obj: any = entidad;
    //obj.modulo = entidad.modulo.modulo;

    const existe = await this.get({ cliente_corporacion: entidad.cliente_corporacion, modulo: entidad.modulo }).toPromise();
    if(existe) {
      if (existe.length > 0) {
        return this.http.put<ClienteCorporacionModulo>(`${GLOBAL.url}/${this.moduleUrl}/cliente_corporacion_modulo.json?cliente_corporacion_modulo=${entidad.cliente_corporacion}&modulo=${entidad.modulo}`, obj, httpOptions).pipe(retry(1), catchError(this.srvcErrHndl.errorHandler)).toPromise();        
      } else {
        return this.http.post<ClienteCorporacionModulo>(`${GLOBAL.url}/${this.moduleUrl}/cliente_corporacion_modulo.json`, obj, httpOptions).pipe(retry(1), catchError(this.srvcErrHndl.errorHandler)).toPromise();        
      }
    } else {
      return this.http.post<ClienteCorporacionModulo>(`${GLOBAL.url}/${this.moduleUrl}/cliente_corporacion_modulo.json`, obj, httpOptions).pipe(retry(1), catchError(this.srvcErrHndl.errorHandler)).toPromise();
    }
  }  
}
