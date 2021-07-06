import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { StorageService } from 'src/app/services/storage.service';
import { LanguageService } from './../../services/language.service';
import { AuthService } from './../../services/auth.service';
import { AppService } from './../../services/app.service';

@Component({
    selector: 'app-user-profile',
    templateUrl: './user-profile.page.html',
    styleUrls: ['./user-profile.page.scss'],
})
export class UserProfilePage implements OnInit {

    form: FormGroup = new FormGroup({
        image: new FormControl(null, []),
        name: new FormControl(null, [Validators.required]),
        email: new FormControl(null, []),
        birthDate: new FormControl(null, [Validators.required]),
        gender: new FormControl(null, [Validators.required]),
        bloodType: new FormControl(null, [Validators.required]),
    });

    bloodTypes = [
        'A+',
        'A-',
        'B+',
        'B-',
        'AB+',
        'AB-',
        'O+',
        'O-',
    ];

    constructor(
        private storageService: StorageService,
        private languageService: LanguageService,
        private authService: AuthService,
        private appService: AppService,
        private router: Router
    ) { }

    ngOnInit() {
    }

    ionViewWillEnter() {
        const user = this.storageService.storageData.user;

        if (!user.email) {
            this.logoutUser();
        }
        this.form.get('name').setValue(user.displayName);
        this.form.get('image').setValue(user.photoURL);
        this.form.get('email').setValue(user.email);
        this.form.get('birthDate').setValue(this.parseBirthDateToForm(user.birthDate));
        this.form.get('bloodType').setValue(user.bloodType);
        this.form.get('gender').setValue(user.gender);
    }

    get image() { return this.form.get('image'); }
    get name() { return this.form.get('name'); }
    get birthDate() { return this.form.get('birthDate'); }
    get gender() { return this.form.get('gender'); }
    get bloodType() { return this.form.get('bloodType'); }

    parseBirthDateToForm(date) {
        if (date) {
            date = date.split(' ');
            return date[0];
        }
        return null;
    }

    logoutUser() {
        const alert = {
            page: 'USER-PROFILE',
            objectReference: 'invalidUser',
            typeAlert: 'warning',
            header: '',
            buttons: ((btnText) => {
                return [{
                    text: btnText.btnConfirm,
                    cssClass: 'secondary',
                    handler: () => {
                        this.authService.signOut();
                    }
                }];
            })
        };
        this.languageService.showAlertInApp(alert);
    }

    receiveImage(image) {
        this.form.get('image').setValue(image.imagem);
    }

    async submit() {
        if (!this.form.valid) {
            this.form.markAllAsTouched();
            return;
        }

        const values = this.form.value;
        let typeHttp = 'post';
        if (this.storageService.storageData.hasData) {
            typeHttp = 'put';
        }

        this.appService.saveUser(values, typeHttp).subscribe(async (response: any) => {
            if (response.status) {
                if (response.data.user) {
                    this.storageService.updateUserStorageData(response.data.user, true);
                    const alert = {
                        page: 'USER-PROFILE',
                        objectReference: 'saveUserSuccess',
                        typeAlert: 'success',
                        header: '',
                        buttons: ((btnText) => {
                            return [{
                                text: btnText.btnConfirm,
                                cssClass: 'secondary',
                                handler: () => {
                                    this.router.navigate(['home']);
                                }
                            }];
                        })
                    };
                    this.languageService.showAlertInApp(alert);
                }
            }
        });
    }

    logoutApp() {
        const alert = {
            page: 'USER-PROFILE',
            objectReference: 'logoutApp',
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
                        this.authService.signOut();
                    }
                }];
            })
        };
        this.languageService.showAlertInApp(alert);
    }
}
