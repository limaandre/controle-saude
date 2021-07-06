
import { Injectable } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { Storage } from '@ionic/storage';
import { AlertController } from '@ionic/angular';

const LNG_KEY = 'SELECTED_LANGUAGE';

@Injectable({
    providedIn: 'root'
})
export class DataFilterService {

    dataFilter = [];
    dataFilterModal = [];

    constructor() { }

    setData(data) {
        this.dataFilter = data;
        return data;
    }

    setDataModal(data) {
        this.dataFilterModal = data;
        return data;
    }

    clearData() {
        this.dataFilter = [];
    }

    clearDataModal() {
        this.dataFilterModal = [];
    }

    getData() {
        return this.dataFilter;
    }

    getDataModal() {
        return this.dataFilterModal;
    }
}
