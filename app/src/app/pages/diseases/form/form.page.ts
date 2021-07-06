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
        note: new FormControl(null, []),
        idDiseases: new FormControl(null, []),
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
        const idDiseases = this.activatedRoute.snapshot.paramMap.get('idDiseases');
        if (idDiseases) {
            this.appService.disease({idDiseases}, 'get').subscribe(async (response: any) => {
                const disease = response.data;
                if (disease?.name) {
                    this.form.get('name').setValue(disease.name);
                    this.form.get('note').setValue(disease.note);
                }
            });
            this.form.get('idDiseases').setValue(idDiseases);
        }
    }

    get name() { return this.form.get('name'); }

    parseDateToForm(date) {
        if (date) {
            date = date.split(' ');
            return date[0];
        }
        return null;
    }

    async submit() {
        const idDiseases = this.activatedRoute.snapshot.paramMap.get('idDiseases');

        if (!this.form.valid) {
            this.form.markAllAsTouched();
            return;
        }

        const values = this.form.value;
        let typeHttp = 'post';
        if (idDiseases) {
            typeHttp = 'put';
        }

        this.appService.disease(values, typeHttp).subscribe(async (response: any) => {
            if (response.status) {
                if (response.data.disease) {
                    const alert = {
                        page: 'DISEASES',
                        objectReference: 'saveSuccess',
                        typeAlert: 'success',
                        header: '',
                        buttons: ((btnText) => {
                            return [{
                                text: btnText.btnConfirm,
                                cssClass: 'secondary',
                                handler: () => {
                                    this.form.reset();
                                    this.router.navigate(['diseases']);
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
            defaultImg: 'assets/img/icons-menu/disease.svg',
            backImg
        };
    }
}
