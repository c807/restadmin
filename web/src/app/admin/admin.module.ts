import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule }   from '@angular/forms';

import { AdminRoutingModule } from './admin-routing.module';
import { MatListModule } from '@angular/material/list';
import { MatIconModule } from '@angular/material/icon';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatCardModule } from '@angular/material/card';
import { MatDividerModule } from '@angular/material/divider';
import { MatTabsModule } from '@angular/material/tabs';
import { MatTableModule } from '@angular/material/table';
import { MatSelectModule } from '@angular/material/select';
import { MatCheckboxModule } from '@angular/material/checkbox';
import { MatButtonModule } from '@angular/material/button';
import { MatSnackBarModule } from '@angular/material/snack-bar';
import { MatToolbarModule } from '@angular/material/toolbar';
import { MatMenuModule } from '@angular/material/menu';

import { SidebarDirective } from './directives/sidebar.directive';

import { LoginComponent } from './components/login/login.component';
import { DashboardComponent } from './components/dashboard/dashboard.component';
import { HeaderComponent } from './components/header/header.component';
import { ClockComponent } from './components/clock/clock.component';
import { MenuComponent } from './components/menu/menu.component';
import { ListaComponent } from './components/cliente/lista/lista.component';
import { ClienteComponent } from './components/cliente/cliente/cliente.component';
import { FormClienteComponent } from './components/cliente/form-cliente/form-cliente.component';
import { FilterPipe } from './pipes/filter.pipe';
import { ModuloComponent } from './components/modulo/modulo/modulo.component';
import { ListaModulosComponent } from './components/modulo/lista-modulos/lista-modulos.component';
import { FormModuloComponent } from './components/modulo/form-modulo/form-modulo.component'


@NgModule({
  declarations: [LoginComponent, DashboardComponent, HeaderComponent, ClockComponent, MenuComponent, SidebarDirective, ListaComponent, ClienteComponent, FormClienteComponent, FilterPipe, ModuloComponent, ListaModulosComponent, FormModuloComponent],
  imports: [
    CommonModule,
    AdminRoutingModule,
    HttpClientModule,
    FormsModule,
    MatListModule,
    MatIconModule,
    MatFormFieldModule,
    MatInputModule,
    MatCardModule,
    MatDividerModule,
    MatTabsModule,
    MatTableModule,
    MatSelectModule,
    MatCheckboxModule,
    MatButtonModule,
    MatSnackBarModule,
    MatToolbarModule,
    MatMenuModule
  ],
  exports: [
    HeaderComponent, MenuComponent
  ]
})
export class AdminModule { }
