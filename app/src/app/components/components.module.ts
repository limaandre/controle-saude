import { SearchDiseaseComponent } from './search-disease/search-disease.component';
import { SearchConsultsComponent } from './search-consults/search-consults.component';
import { FormModalSearchComponent } from './form-modal-search/form-modal-search.component';
import { SearchNotesComponent } from './search-notes/search-notes.component';
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { IonicModule } from '@ionic/angular';

import { FabComponent } from './fab/fab.component';
import { ImagemComponent } from './imagem/imagem.component';
import { HeaderComponent } from './header/header.component';
import { UploadImageComponent } from './upload-image/upload-image.component';
import { SearchDataComponent } from './search-data/search-data.component';
import { TranslateModule } from '@ngx-translate/core';
import { SearchDoctorComponent } from './search-doctor/search-doctor.component';
import { SearchModalComponent } from './search-modal/search-modal.component';
import { SearchExamComponent } from './search-exam/search-exam.component';
import { SearchMedicationComponent } from './search-medication/search-medication.component';

@NgModule({
  declarations: [
    FabComponent,
    ImagemComponent,
    HeaderComponent,
    UploadImageComponent,
    SearchDataComponent,
    SearchModalComponent,
    SearchDoctorComponent,
    SearchNotesComponent,
    SearchExamComponent,
    SearchConsultsComponent,
    SearchDiseaseComponent,
    SearchMedicationComponent,
    FormModalSearchComponent
  ],
  imports: [
    CommonModule,
    FormsModule,
    TranslateModule,
    IonicModule.forRoot(),
  ],
  exports: [
    FabComponent,
    ImagemComponent,
    HeaderComponent,
    UploadImageComponent,
    SearchDataComponent,
    SearchDoctorComponent,
    SearchModalComponent,
    SearchExamComponent,
    SearchNotesComponent,
    SearchConsultsComponent,
    SearchDiseaseComponent,
    SearchMedicationComponent,
    FormModalSearchComponent
  ],
})
export class ComponentsModule {}
