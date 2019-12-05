import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { GLOBAL } from '../../shared/global';
import { ServiceErrorHandler } from '../../shared/error-handler';
import { usrLogin, usrLogInResponse, Usuario } from '../models/usuario';
import { LocalstorageService } from '../services/localstorage.service';
import { Observable } from 'rxjs';
import { retry, catchError } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class UsuarioService {

  private srvcErrHndl: ServiceErrorHandler;
  private moduleUrl: string = 'usuario';
  private usrToken: string = null;
  private headersObj: any;

  constructor(
    private http: HttpClient,
    private ls: LocalstorageService
  ) {
    this.srvcErrHndl = new ServiceErrorHandler();
    this.setToken();
  }

  private setToken() {
    this.usrToken = this.ls.get(GLOBAL.usrTokenVar) ? this.ls.get(GLOBAL.usrTokenVar).token : null;
    //GLOBAL.sharedHeaders['Authorization'] = this.usrToken;
  }

  login(usr: usrLogin): Observable<usrLogInResponse> {
    const obj = {
      usr: usr.usuario,
      pwd: usr.contrasenia
    };

    const httpOptions = {
      headers: new HttpHeaders(GLOBAL.sharedHeaders)
    };

    return this.http.post<usrLogInResponse>(`${GLOBAL.url}/${this.moduleUrl}/login.json`, JSON.stringify(obj), httpOptions).pipe(retry(1), catchError(this.srvcErrHndl.errorHandler));
  }

  getAll(debaja: number = 0): Observable<Usuario> {
    GLOBAL.sharedHeaders['Authorization'] = this.usrToken;
    const httpOptions = {
      headers: new HttpHeaders(GLOBAL.sharedHeaders)
    };

    return this.http.get<Usuario>(`${GLOBAL.url}/${this.moduleUrl}/usuarios.json?debaja=${debaja}`, httpOptions).pipe(retry(1), catchError(this.srvcErrHndl.errorHandler));
  }

  async checkUserToken() {
    this.setToken();
    if (this.usrToken) {
      GLOBAL.sharedHeaders['Authorization'] = this.usrToken;
      const httpOptions = {
        headers: new HttpHeaders(GLOBAL.sharedHeaders)
      };
      const resp: any = await this.http.get(`${GLOBAL.url}/${this.moduleUrl}/checktoken.json`, httpOptions).toPromise();
      if (resp.valido) {
        return resp.valido;
      } else {
        return false;
      }
    }
    return false;
  }
}
