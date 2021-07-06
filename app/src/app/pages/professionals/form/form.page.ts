import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { StorageService } from 'src/app/services/storage.service';
import { LanguageService } from '../../../services/language.service';
import { AppService } from '../../../services/app.service';
import { AuthService } from 'src/app/services/auth.service';

@Component({
    selector: 'app-form',
    templateUrl: './form.page.html',
    styleUrls: ['./form.page.scss'],
})
export class FormPage implements OnInit {

    form: FormGroup = new FormGroup({
        name: new FormControl(null, [Validators.required]),
        medicalSpecialization: new FormControl(null, []),
        address: new FormControl(null, []),
        phoneNumber: new FormControl(null, []),
        email: new FormControl(null, []),
        image: new FormControl(null, []),
        idDoctor: new FormControl(null, []),
        // file: new FormControl(null, []),
    });

    constructor(
        private storageService: StorageService,
        private languageService: LanguageService,
        private appService: AppService,
        private router: Router,
        private activatedRoute: ActivatedRoute,
        private authService: AuthService,
    ) { }

    ngOnInit() {
    }

    ionViewWillEnter() {
        const user = this.storageService.storageData.user;
        if (!user.email) {
            this.authService.logoutUser();
        }
        const idDoctor = this.activatedRoute.snapshot.paramMap.get('idDoctor');
        if (idDoctor) {
            this.appService.doctor({idDoctor}, 'get').subscribe(async (response: any) => {
                const doctor = response.data;
                if (doctor?.name) {
                    this.form.get('name').setValue(doctor.name);
                    this.form.get('image').setValue(doctor.image);
                    this.form.get('email').setValue(doctor.email);
                    this.form.get('medicalSpecialization').setValue(doctor.medicalSpecialization);
                    this.form.get('address').setValue(doctor.address);
                    this.form.get('phoneNumber').setValue(doctor.phoneNumber);
                }
            });
            this.form.get('idDoctor').setValue(idDoctor);
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
        const idDoctor = this.activatedRoute.snapshot.paramMap.get('idDoctor');

        if (!this.form.valid) {
            this.form.markAllAsTouched();
            return;
        }

        const values = this.form.value;
        let typeHttp = 'post';
        if (idDoctor) {
            typeHttp = 'put';
        }

        this.appService.doctor(values, typeHttp).subscribe(async (response: any) => {
            if (response.status) {
                if (response.data.doctor) {
                    const alert = {
                        page: 'PROFESSIONALS',
                        objectReference: 'saveSuccess',
                        typeAlert: 'success',
                        header: '',
                        buttons: ((btnText) => {
                            return [{
                                text: btnText.btnConfirm,
                                cssClass: 'secondary',
                                handler: () => {
                                    this.form.reset();
                                    this.router.navigate(['professionals']);
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
            defaultImg: 'assets/img/icons-menu/doctor.svg',
            backImg
        };
    }
}
