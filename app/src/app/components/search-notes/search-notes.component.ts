import { Component, OnInit, Input } from '@angular/core';
import { ModalController } from '@ionic/angular';
import { Notes } from 'src/app/interfaces/Notes';
import { AppService } from 'src/app/services/app.service';
import { DataFilterService } from 'src/app/services/data-filter.service';
import { SearchModalComponent } from './../search-modal/search-modal.component';

@Component({
    selector: 'app-search-notes',
    templateUrl: './search-notes.component.html',
    styleUrls: ['./search-notes.component.scss'],
})
export class SearchNotesComponent implements OnInit {

    @Input() dataFilter: Array<Notes>;
    @Input() type: string;

    icons = {
        title: 'text-outline',
        note: 'document-text-outline',
        main: 'notes'
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
                if (key !== 'idAnnotation' && key !== 'show' && key !== 'filter') {
                    newData.push(
                        { key, data: data[key] }
                    );
                }
            }
        }
        return newData;
    }

    async openData(data) {
        const id = data.idAnnotation;
        const formLabel = 'NOTES.form';
        const headerText = 'NOTES.textHeader';
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
                    this.appService.notes({ search: '' }, 'get').subscribe(async (response: any) => {
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
            defaultImg: 'assets/img/icons-menu/notes.svg',
            backImg
        };
    }

}
