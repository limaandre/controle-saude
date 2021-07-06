import { Component, OnInit, Input } from '@angular/core';
import { ModalController } from '@ionic/angular';
import { Exam } from 'src/app/interfaces/Exam';
import { AppService } from 'src/app/services/app.service';
import { DataFilterService } from 'src/app/services/data-filter.service';
import { SearchModalComponent } from './../search-modal/search-modal.component';


@Component({
    selector: 'app-search-exam',
    templateUrl: './search-exam.component.html',
    styleUrls: ['./search-exam.component.scss'],
})
export class SearchExamComponent implements OnInit {

    @Input() dataFilter: Array<Exam>;
    @Input() type: string;

    icons = {
        name: 'pulse-outline',
        date: 'calendar-outline',
        hour: 'alarm-outline',
        doctor: 'person-circle-outline',
        locale: 'location-outline',
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
                if (key !== 'idExam' && key !== 'show' && key !== 'filter' && key !== 'idDoctor' && key !== 'date') {
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
        const id = data.idExam;
        const formLabel = 'EXAMS.form';
        const headerText = 'EXAMS.textHeader';
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
                    this.appService.exam({ search: '' }, 'get').subscribe(async (response: any) => {
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
            defaultImg: 'assets/img/icons-menu/exams.svg',
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
