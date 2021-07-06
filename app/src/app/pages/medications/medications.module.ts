import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { MedicationssPageRoutingModule } from './medications-routing.module';


@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    MedicationssPageRoutingModule
  ],
  declarations: []
})
export class MedicationsPageModule {}
