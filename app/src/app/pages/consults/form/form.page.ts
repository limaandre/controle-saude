import { FormModalSearchComponent } from './../../../components/form-modal-search/form-modal-search.component';
import { Component, Input, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { StorageService } from 'src/app/services/storage.service';
import { LanguageService } from '../../../services/language.service';
import { AppService } from '../../../services/app.service';
import { AuthService } from 'src/app/services/auth.service';
import { ModalController } from '@ionic/angular';
import { Doctor } from 'src/app/interfaces/Doctor';
import { DataFilterService } from 'src/app/services/data-filter.service';

@Component({
    selector: 'app-form',
    templateUrl: './form.page.html',
    styleUrls: ['./form.page.scss'],
})
export class FormPage implements OnInit {

    @Input() itemSelected: any;
    dataFilter: Array<Doctor>;
    minDate;
    form: FormGroup = new FormGroup({
        date: new FormControl(null, []),
        hour: new FormControl(null, []),
        locale: new FormControl(null, [Validators.required]),
        note: new FormControl(null, []),
        doctor: new FormControl(null, [Validators.required]),
        image: new FormControl(null, []),
        idConsult: new FormControl(null, []),
        idDoctor: new FormControl(null, []),
        // file: new FormControl(null, []),
    });

    constructor(
        private modalController: ModalController,
        private storageService: StorageService,
        private languageService: LanguageService,
        private appService: AppService,
        private router: Router,
        private activatedRoute: ActivatedRoute,
        private authService: AuthService,
        private dataFilterService: DataFilterService
    ) { }

    ngOnInit() {
        this.setMinDate();
    }

    setMinDate() {
        const today = new Date();
        today.setDate(today.getDate() - 1);
        const dd = String(today.getDate()).padStart(2, '0');
        const mm = String(today.getMonth() + 1).padStart(2, '0');
        const yy = today.getFullYear();
        this.minDate = yy + '-' + mm + '-' + dd;
    }

    ionViewWillEnter() {
        const user = this.storageService.storageData.user;
        if (!user.email) {
            this.authService.logoutUser();
        }
        const idConsult = this.activatedRoute.snapshot.paramMap.get('idConsult');
        this.getProfessionals('');
        if (idConsult) {
            this.appService.consult({ idConsult }, 'get').subscribe(async (response: any) => {
                const consult = response.data;
                if (consult?.locale) {
                    this.form.get('date').setValue(consult.date);
                    this.form.get('hour').setValue(consult.hour);
                    this.form.get('locale').setValue(consult.locale);
                    this.form.get('note').setValue(consult.note);
                    this.form.get('doctor').setValue(consult.doctor);
                    this.form.get('idDoctor').setValue(consult.idDoctor);
                    this.form.get('image').setValue(consult.image);
                }
            });
            this.form.get('idConsult').setValue(idConsult);
        }
    }

    getProfessionals(textSearch) {
        this.appService.doctor({ search: textSearch }, 'get').subscribe(async (response: any) => {
            if (response.data) {
                this.dataFilterService.setDataModal(response.data);
            }
        });
    }

    get image() { return this.form.get('image'); }
    get doctor() { return this.form.get('doctor'); }
    get locale() { return this.form.get('locale'); }

    parseDateToForm(date) {
        if (date) {
            date = date.split(' ');
            return date[0];
        }
        return null;
    }

    receiveImage(image) {
        this.form.get('image').setValue(image.imagem);
    }

    async submit() {
        const idConsult = this.activatedRoute.snapshot.paramMap.get('idConsult');

        if (!this.form.valid) {
            this.form.markAllAsTouched();
            return;
        }

        const values = this.form.value;
        let typeHttp = 'post';
        if (idConsult) {
            typeHttp = 'put';
        }

        this.appService.consult(values, typeHttp).subscribe(async (response: any) => {
            if (response.status) {
                if (response.data.consults) {
                    const alert = {
                        page: 'CONSULTS',
                        objectReference: 'saveSuccess',
                        typeAlert: 'success',
                        header: '',
                        buttons: ((btnText) => {
                            return [{
                                text: btnText.btnConfirm,
                                cssClass: 'secondary',
                                handler: () => {
                                    this.form.reset();
                                    this.router.navigate(['consults']);
                                }
                            }];
                        })
                    };
                    this.languageService.showAlertInApp(alert);
                }
            }
        });
    }

    mountImage(backImg) {
        return {
            defaultImg: 'assets/img/icons-menu/consults.svg',
            backImg
        };
    }

    async addProfessional() {
        const validIfIsOpened = await this.modalController.getTop();
        if (!validIfIsOpened) {
            const modal = await this.modalController.create({
                component: FormModalSearchComponent,
                cssClass: 'my-custom-class',
                componentProps: {
                    type: 'professionals',
                    headerText: 'PROFESSIONALS.textHeader',
                    defaultImg: 'assets/img/icons-menu/doctor.svg',
                }
            });

            modal.onDidDismiss()
                .then((data: any) => {
                    if (data?.data?.name) {
                        this.form.get('doctor').setValue(data.data.name);
                        this.form.get('idDoctor').setValue(data.data.idDoctor);
                    }
                });

            await modal.present();
        }
    }
}
