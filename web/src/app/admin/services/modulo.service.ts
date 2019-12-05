import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { GLOBAL } from '../../shared/global';
import { ServiceErrorHandler } from '../../shared/error-handler';
import { Modulo } from '../models/modulo';
import { LocalstorageService } from '../services/localstorage.service';
import { Observable } from 'rxjs';
import { retry, catchError } from 'rxjs/operators';
import * as qs from 'qs';

@Injectable({
  providedIn: 'root'
})
export class ModuloService {

  private srvcErrHndl: ServiceErrorHandler;
  private moduleUrl: string = 'modulo';
  private usrToken: string = null;

  constructor(
    private http: HttpClient,
    private ls: LocalstorageService
  ) {
    this.srvcErrHndl = new ServiceErrorHandler();
    this.usrToken = this.ls.get(GLOBAL.usrTokenVar) ? this.ls.get(GLOBAL.usrTokenVar).token : null;
  }
  
  getAll(): Observable<Modulo[]> {
    GLOBAL.sharedHeaders['Authorization'] = this.usrToken;
    const httpOptions = {
      headers: new HttpHeaders(GLOBAL.sharedHeaders)
    };    
    return this.http.get<Modulo[]>(`${GLOBAL.url}/${this.moduleUrl}/modulos.json`, httpOptions).pipe(retry(1), catchError(this.srvcErrHndl.errorHandler));
  }

  get(fltr: any): Observable<Modulo[]> {
    GLOBAL.sharedHeaders['Authorization'] = this.usrToken;
    const httpOptions = {
      headers: new HttpHeaders(GLOBAL.sharedHeaders)
    };    
    return this.http.get<Modulo[]>(`${GLOBAL.url}/${this.moduleUrl}/modulo.json?${qs.stringify(fltr)}`, httpOptions).pipe(retry(1), catchError(this.srvcErrHndl.errorHandler));
  }

  save(entidad: Modulo): Observable<Modulo> {
    GLOBAL.sharedHeaders['Authorization'] = this.usrToken;
    const httpOptions = {
      headers: new HttpHeaders(GLOBAL.sharedHeaders)
    };

    const obj: any = entidad;

    if(entidad.modulo){
      return this.http.put<Modulo>(`${GLOBAL.url}/${this.moduleUrl}/modulo.json?modulo=${entidad.modulo}`, obj, httpOptions).pipe(retry(1), catchError(this.srvcErrHndl.errorHandler));
    } else {
      delete entidad.modulo;
      return this.http.post<Modulo>(`${GLOBAL.url}/${this.moduleUrl}/modulo.json`, obj, httpOptions).pipe(retry(1), catchError(this.srvcErrHndl.errorHandler));
    }
  }  
}
