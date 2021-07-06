import { Component, OnInit, Input } from '@angular/core';
import { ModalController } from '@ionic/angular';
import { Medications } from 'src/app/interfaces/Medications';
import { AppService } from 'src/app/services/app.service';
import { DataFilterService } from 'src/app/services/data-filter.service';
import { SearchModalComponent } from './../search-modal/search-modal.component';


@Component({
    selector: 'app-search-medication',
    templateUrl: './search-medication.component.html',
    styleUrls: ['./search-medication.component.scss'],
})
export class SearchMedicationComponent implements OnInit {

    @Input() dataFilter: Array<Medications>;
    @Input() type: string;

    icons = {
        name: 'person-circle-outline',
        concentration: 'water-outline',
        dosage: 'wine-outline',
        prescription: 'document-text-outline',
        dateInitial: 'calendar-outline',
        dateEnd: 'calendar-outline',
        medicationSchedules: 'time-outline',
        doctor: 'person-circle-outline',
        note: 'document-text-outline',
        main: 'exams'
    };

    constructor(
        public modalController: ModalController,
        private appService: AppService,
        private dataFilterService: DataFilterService
    ) { }

    ngOnInit() { }

    parseData(data) {
        const newData = [];
        for (const key in data) {
            if (Object.prototype.hasOwnProperty.call(data, key)) {
                if (key !== 'idMedication' && key !== 'show' && key !== 'filter' && key !== 'idDoctor' && key !== 'dateEnd' && key !== 'dateInitial') {
                    newData.push(
                        { key, data: data[key] }
                    );
                } else if ( (key === 'dateInitial' || key === 'dateEnd') && data[key]) {
                    newData.push(
                        { key, data: this.parseDate(data[key], null) }
                    );
                }
            }
        }
        return newData;
    }

    async openData(data) {
        const id = data.idMedication;
        const formLabel = 'MEDICATIONS.form';
        const headerText = 'MEDICATIONS.textHeader';
        const icons = this.icons;
        const type = this.type;
        data = this.parseData(data);
        const modal = await this.modalController.create({
            component: SearchModalComponent,
            cssClass: 'my-custom-class',
            componentProps: { data, icons, formLabel, headerText, type, id }

        });

        modal.onDidDismiss()
            .then((dateReturn: any) => {
                if (dateReturn?.data?.delete) {
                    this.appService.medication({ search: '' }, 'get').subscribe(async (response: any) => {
                        if (response.data) {
                            this.dataFilter = this.dataFilterService.setData(response.data);
                        }
                    });
                }
            });

        await modal.present();
    }

    mountImage(backImg) {
        return {
            defaultImg: 'assets/img/icons-menu/medications.svg',
            backImg
        };
    }

    parseDate(date, hour) {
        if (date) {
            date = new Date(date + 'T08:00:00');
            return `${date.toLocaleDateString('pt-BR')}  ${hour ? hour : ''}`;
        }
        return;
    }


}
