import { Component, OnInit, ViewChild } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { StorageService } from 'src/app/services/storage.service';
import { LanguageService } from '../../../services/language.service';
import { AppService } from '../../../services/app.service';
import { AuthService } from 'src/app/services/auth.service';
import { ModalController } from '@ionic/angular';
import { FormModalSearchComponent } from './../../../components/form-modal-search/form-modal-search.component';
import { DataFilterService } from 'src/app/services/data-filter.service';


@Component({
    selector: 'app-form',
    templateUrl: './form.page.html',
    styleUrls: ['./form.page.scss'],
})
export class FormPage implements OnInit {

    @ViewChild('auxMedicationSchedules') datePicker;

    form: FormGroup = new FormGroup({
        name: new FormControl(null, [Validators.required]),
        concentration: new FormControl(null, []),
        dosage: new FormControl(null, []),
        medicationSchedules: new FormControl(['15:30', '16:21'], []),
        auxMedicationSchedules: new FormControl(null, []),
        dateInitial: new FormControl(null, []),
        dateEnd: new FormControl(null, []),
        prescription: new FormControl(null, []),
        doctor: new FormControl(null, []),
        idMedication: new FormControl(null, []),
        note: new FormControl(null, []),
        image: new FormControl(null, []),
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
        private dataFilterService: DataFilterService,
        private authService: AuthService,
    ) { }

    ngOnInit() {
    }

    ionViewWillEnter() {
        const user = this.storageService.storageData.user;
        if (!user.email) {
            this.authService.logoutUser();
        }

        const idMedication = this.activatedRoute.snapshot.paramMap.get('idMedication');
        this.getProfessionals('');
        if (idMedication) {
            this.appService.medication({ idMedication }, 'get').subscribe(async (response: any) => {
                const medication = response.data;
                if (medication?.name) {
                    this.form.get('name').setValue(medication.name);
                    this.form.get('image').setValue(medication.image);
                    this.form.get('concentration').setValue(medication.concentration);
                    this.form.get('dosage').setValue(medication.dosage);
                    this.form.get('medicationSchedules').setValue(medication.medicationSchedules);
                    this.form.get('dateInitial').setValue(medication.dateInitial);
                    this.form.get('dateEnd').setValue(medication.dateEnd);
                    this.form.get('prescription').setValue(medication.prescription);
                    this.form.get('note').setValue(medication.note);
                    this.form.get('doctor').setValue(medication.doctor);
                    this.form.get('idDoctor').setValue(medication.idDoctor);
                }
            });
            this.form.get('idMedication').setValue(idMedication);
        }
    }

    get name() { return this.form.get('name'); }
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
        const idMedication = this.activatedRoute.snapshot.paramMap.get('idMedication');

        if (!this.form.valid) {
            this.form.markAllAsTouched();
            return;
        }

        const values = this.form.value;
        let typeHttp = 'post';
        if (idMedication) {
            typeHttp = 'put';
        }

        this.appService.medication(values, typeHttp).subscribe(async (response: any) => {
            if (response.status) {
                if (response.data.medicine) {
                    const alert = {
                        page: 'MEDICATIONS',
                        objectReference: 'saveSuccess',
                        typeAlert: 'success',
                        header: '',
                        buttons: ((btnText) => {
                            return [{
                                text: btnText.btnConfirm,
                                cssClass: 'secondary',
                                handler: () => {
                                    this.form.reset();
                                    this.router.navigate(['medications']);
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
            defaultImg: 'assets/img/icons-menu/medications.svg',
            backImg
        };
    }

    async focusSearch() {
        const auxMedicationSchedules = document.getElementById('auxMedicationSchedules').firstChild as HTMLElement;
        auxMedicationSchedules.click();
        auxMedicationSchedules.focus();
        document.getElementById('auxMedicationSchedules').click();
        document.getElementById('auxMedicationSchedules').focus();
    }

    changeDate(e) {
        const values = this.form.value.medicationSchedules;
        if (e.detail?.value) {
            values.push(e.detail.value);
            this.form.get('medicationSchedules').setValue(values);
        }
    }

    removeHour(index) {
        const values = this.form.value.medicationSchedules;
        values.splice(index, 1);
        this.form.get('medicationSchedules').setValue(values);
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

    getProfessionals(textSearch) {
        this.appService.doctor({ search: textSearch }, 'get').subscribe(async (response: any) => {
            if (response.data) {
                this.dataFilterService.setDataModal(response.data);
            }
        });
    }
}
