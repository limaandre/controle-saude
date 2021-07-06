import { Component, OnInit, Input } from '@angular/core';
import { ModalController } from '@ionic/angular';
import { Doctor } from 'src/app/interfaces/Doctor';
import { AppService } from 'src/app/services/app.service';
import { DataFilterService } from 'src/app/services/data-filter.service';
import { SearchModalComponent } from './../search-modal/search-modal.component';


@Component({
    selector: 'app-search-doctor',
    templateUrl: './search-doctor.component.html',
    styleUrls: ['./search-doctor.component.scss'],
})
export class SearchDoctorComponent implements OnInit {
    @Input() dataFilter: Array<Doctor>;
    @Input() type: string;

    icons = {
        name: 'person-circle-outline',
        email: 'mail-outline',
        medicalSpecialization: 'bag-outline',
        address: 'location-outline',
        phoneNumber: 'call-outline',
        main: 'doctor'
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
                if (key !== 'idDoctor' && key !== 'show' && key !== 'filter') {
                    newData.push(
                        { key, data: data[key] }
                    );
                }
            }
        }
        return newData;
    }

    async openData(data) {
        const id = data.idDoctor;
        const formLabel = 'PROFESSIONALS.form';
        const headerText = 'PROFESSIONALS.textHeader';
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
                    this.appService.doctor({ search: '' }, 'get').subscribe(async (response: any) => {
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
            defaultImg: 'assets/img/icons-menu/doctor.svg',
            backImg
        };
    }

}
