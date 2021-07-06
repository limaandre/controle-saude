import { LanguageService } from './../../services/language.service';
import { AppService } from './../../services/app.service';
import { Component, Input, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { ModalController } from '@ionic/angular';

@Component({
    selector: 'app-search-modal',
    templateUrl: './search-modal.component.html',
    styleUrls: ['./search-modal.component.scss'],
})
export class SearchModalComponent implements OnInit {

    @Input() data: any;
    @Input() icons: any;
    @Input() formLabel: any;
    @Input() headerText: any;
    @Input() type: any;
    @Input() id: any;

    image: string;

    constructor(
        private router: Router,
        private modalController: ModalController,
        private appService: AppService,
        private languageService: LanguageService
    ) { }

    ngOnInit() {
        this.image = null;
        if (this.data) {
            this.validateImage();
        }
    }

    validateImage() {
        this.data.forEach(element => {
            if (element.key === 'image') {
                this.image = element.data;
            }
        });
    }

    dismissModal() {
        this.modalController.dismiss();
    }

    editData() {
        this.dismissModal();
        this.router.navigate([this.type + '/form/' + this.id]);
    }

    mountImage(backImg, defaultImg) {
        return {
            defaultImg,
            backImg
        };
    }


    deleteData() {
        const alert = {
            page: 'DELETE-DATA',
            objectReference: 'msgValidate',
            typeAlert: 'warning',
            header: '',
            buttons: ((btnText) => {
                return [{
                    text: btnText.btnCancel,
                    cssClass: 'secondary'
                }, {
                    text: btnText.btnConfirm,
                    cssClass: 'secondary',
                    handler: () => {
                        this.execRemove();
                    }
                }];
            })
        };
        this.languageService.showAlertInApp(alert);
    }

    execRemove() {
        this.appService.deleteData({
            type: this.type,
            id: this.id
        }).subscribe(async (response: any) => {
            if (response.status) {
                const alert = {
                    page: 'DELETE-DATA',
                    objectReference: 'successMsg',
                    typeAlert: 'success',
                    header: '',
                    buttons: ((btnText) => {
                        return [{
                            text: btnText.btnConfirm,
                            cssClass: 'secondary',
                            handler: () => {
                                this.modalController.dismiss({delete: true});
                            }
                        }];
                    })
                };
                this.languageService.showAlertInApp(alert);
            }
        });
    }


}
