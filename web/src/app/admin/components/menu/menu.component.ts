import { Component, OnInit, Output, EventEmitter } from '@angular/core';

@Component({
  selector: 'app-menu',
  templateUrl: './menu.component.html',
  styleUrls: ['./menu.component.css']
})
export class MenuComponent implements OnInit {

  @Output() public elementClicked = new EventEmitter();

  constructor() { }

  ngOnInit() {
  }

  itemClicked(){
    this.elementClicked.emit();
  }

}
