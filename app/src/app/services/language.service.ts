
import { Injectable } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { Storage } from '@ionic/storage';
import { AlertController } from '@ionic/angular';

const LNG_KEY = 'SELECTED_LANGUAGE';

@Injectable({
    providedIn: 'root'
})
export class LanguageService {
    // https://www.youtube.com/watch?v=WEh_GY5gpkg
    selected = '';

    constructor(
        private translate: TranslateService,
        private storage: Storage,
        public alertController: AlertController
    ) { }

    setInitialAppLanguage() {
        this.getLanguageStorage();
    }

    async getLanguageStorage() {
        await this.storage.get(LNG_KEY).then(val => {
            const lng = val ? val : this.translate.getBrowserLang();
            this.setLanguage(lng);
            this.translate.setDefaultLang(lng);
        });
    }

    getLanguages() {
        return [
            { text: 'English', value: 'en' },
            { text: 'Portuguese', value: 'pt' }
        ];
    }

    setLanguage(lng: string) {
        this.selected = lng;
        this.translate.use(lng);
        this.storage.set(LNG_KEY, lng);
    }

    showAlertInApp(alertInfo) {
        this.translate.getTranslation(this.selected).subscribe( async value => {
            const { page, objectReference, header, buttons } = alertInfo;
            const msg = value[page][objectReference];
            const alertComponent = value['ALERT-COMPONENT'];

            const alert = await this.alertController.create({
                cssClass: 'my-custom-class',
                header,
                message: msg,
                buttons: buttons(alertComponent)
            });

            await alert.present();
        });
    }

    getTextTranslate() {
        return this.translate.getTranslation(this.selected);
    }
}
