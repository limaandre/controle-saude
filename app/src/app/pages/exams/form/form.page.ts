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
        name: new FormControl(null, [Validators.required]),
        date: new FormControl(null, []),
        hour: new FormControl(null, []),
        locale: new FormControl(null, []),
        note: new FormControl(null, []),
        doctor: new FormControl(null, [Validators.required]),
        image: new FormControl(null, []),
        idExam: new FormControl(null, []),
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
        const idExam = this.activatedRoute.snapshot.paramMap.get('idExam');
        this.getProfessionals('');
        if (idExam) {
            this.appService.exam({ idExam }, 'get').subscribe(async (response: any) => {
                const exam = response.data;
                if (exam?.name) {
                    this.form.get('name').setValue(exam.name);
                    this.form.get('date').setValue(exam.date);
                    this.form.get('hour').setValue(exam.hour);
                    this.form.get('locale').setValue(exam.locale);
                    this.form.get('note').setValue(exam.note);
                    this.form.get('doctor').setValue(exam.doctor);
                    this.form.get('idDoctor').setValue(exam.idDoctor);
                    this.form.get('image').setValue(exam.image);
                }
            });
            this.form.get('idExam').setValue(idExam);
        }
    }

    getProfessionals(textSearch) {
        this.appService.doctor({ search: textSearch }, 'get').subscribe(async (response: any) => {
            if (response.data) {
                this.dataFilterService.setDataModal(response.data);
            }
        });
    }

    get name() { return this.form.get('name'); }
    get doctor() { return this.form.get('doctor'); }
    get image() { return this.form.get('image'); }

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
        const idExam = this.activatedRoute.snapshot.paramMap.get('idExam');

        if (!this.form.valid) {
            this.form.markAllAsTouched();
            return;
        }

        const values = this.form.value;
        let typeHttp = 'post';
        if (idExam) {
            typeHttp = 'put';
        }

        this.appService.exam(values, typeHttp).subscribe(async (response: any) => {
            if (response.status) {
                if (response.data.exams) {
                    const alert = {
                        page: 'EXAMS',
                        objectReference: 'saveSuccess',
                        typeAlert: 'success',
                        header: '',
                        buttons: ((btnText) => {
                            return [{
                                text: btnText.btnConfirm,
                                cssClass: 'secondary',
                                handler: () => {
                                    this.form.reset();
                                    this.router.navigate(['exams']);
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
            defaultImg: 'assets/img/icons-menu/exams.svg',
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
