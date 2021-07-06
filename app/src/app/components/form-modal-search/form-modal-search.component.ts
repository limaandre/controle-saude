import { Component, OnInit, ViewChild, Input } from '@angular/core';
import { Router } from '@angular/router';
import { IonInfiniteScroll, ModalController } from '@ionic/angular';
import { AppService } from 'src/app/services/app.service';
import { DataFilterService } from 'src/app/services/data-filter.service';

@Component({
    selector: 'app-form-modal-search',
    templateUrl: './form-modal-search.component.html',
    styleUrls: ['./form-modal-search.component.scss'],
})
export class FormModalSearchComponent implements OnInit {

    @Input() searchData: (args: any) => void;
    @Input() type: string;
    @Input() headerText: any;
    @Input() defaultImg: any;


    @ViewChild(IonInfiniteScroll) infiniteScroll: IonInfiniteScroll;

    constructor(
        private router: Router,
        public dataFilterService: DataFilterService,
        private modalController: ModalController

    ) { }

    ngOnInit() { }

    onClear(e) {
        this.filterData(null);
    }

    onChange(e) {
        this.filterData(e);
    }

    filterData(e) {
        this.dataFilterService.dataFilterModal = this.dataFilterService.dataFilterModal.map(data => {
            data.show = true;
            const dados = JSON.stringify(data).toLowerCase();
            if (e && e.detail?.value) {
                e.detail.value = e.detail.value.toLowerCase();
                if (e && !dados.includes(e.detail.value)) {
                    data.show = false;
                }
            }
            return data;
        });
    }

    newData() {
        this.dismissModal();
        this.router.navigate([this.type + '/form']);
    }

    dismissModal() {
        this.modalController.dismiss();
    }

    setData(data) {
        this.modalController.dismiss(data);
    }

    mountImage(backImg) {
        return {
            defaultImg: this.defaultImg,
            backImg
        };
    }

}
