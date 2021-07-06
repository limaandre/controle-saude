import { Component, OnInit, Input } from '@angular/core';
import { ModalController } from '@ionic/angular';
import { Consult } from 'src/app/interfaces/Consult';
import { AppService } from 'src/app/services/app.service';
import { DataFilterService } from 'src/app/services/data-filter.service';
import { SearchModalComponent } from './../search-modal/search-modal.component';

@Component({
    selector: 'app-search-consults',
    templateUrl: './search-consults.component.html',
    styleUrls: ['./search-consults.component.scss'],
})
export class SearchConsultsComponent implements OnInit {

    @Input() dataFilter: Array<Consult>;
    @Input() type: string;

    icons = {
        locale: 'location-outline',
        date: 'calendar-outline',
        hour: 'alarm-outline',
        doctor: 'person-circle-outline',
        note: 'document-text-outline',
        main: 'consults'
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
                if (key !== 'idconsult' && key !== 'show' && key !== 'filter' && key !== 'idDoctor' && key !== 'date') {
                    newData.push(
                        { key, data: data[key] }
                    );
                } else if (key === 'date' && data[key]) {
                    newData.push(
                        { key, data: this.parseDate(data[key], null) }
                    );
                }
            }
        }
        return newData;
    }

    async openData(data) {
        const id = data.idconsult;
        const formLabel = 'CONSULTS.form';
        const headerText = 'CONSULTS.textHeader';
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
                    this.appService.consult({ search: '' }, 'get').subscribe(async (response: any) => {
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
            defaultImg: 'assets/img/icons-menu/consults.svg',
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
