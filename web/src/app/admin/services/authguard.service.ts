import { Injectable } from '@angular/core';
import { Router, CanActivate } from '@angular/router';
import { UsuarioService } from './usuario.service';

@Injectable({
  providedIn: 'root'
})
export class AuthguardService implements CanActivate {

  constructor(private usrSrvc: UsuarioService, private router: Router) { }

  async canActivate(): Promise<boolean> {
    const valido = await this.usrSrvc.checkUserToken();
    if(valido){
      return true;
    } else {
      this.router.navigate(['/admin/login']);
      return false;
    }
  }
}
