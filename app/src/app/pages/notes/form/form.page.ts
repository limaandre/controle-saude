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
        note: new FormControl(null, [Validators.required]),
        title: new FormControl(null, []),
        image: new FormControl(null, []),
        idAnnotation: new FormControl(null, []),
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
        const idAnnotation = this.activatedRoute.snapshot.paramMap.get('idAnnotation');
        if (idAnnotation) {
            this.appService.notes({idAnnotation}, 'get').subscribe(async (response: any) => {
                const notes = response.data;
                if (notes?.note) {
                    this.form.get('title').setValue(notes.title);
                    this.form.get('image').setValue(notes.image);
                    this.form.get('note').setValue(notes.note);
                }
            });
            this.form.get('idAnnotation').setValue(idAnnotation);
        }
    }

    get note() { return this.form.get('note'); }
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
        const idAnnotation = this.activatedRoute.snapshot.paramMap.get('idAnnotation');

        if (!this.form.valid) {
            this.form.markAllAsTouched();
            return;
        }

        const values = this.form.value;
        let typeHttp = 'post';
        if (idAnnotation) {
            typeHttp = 'put';
        }

        this.appService.notes(values, typeHttp).subscribe(async (response: any) => {
            if (response.status) {
                if (response.data.notes) {
                    const alert = {
                        page: 'NOTES',
                        objectReference: 'saveSuccess',
                        typeAlert: 'success',
                        header: '',
                        buttons: ((btnText) => {
                            return [{
                                text: btnText.btnConfirm,
                                cssClass: 'secondary',
                                handler: () => {
                                    this.form.reset();
                                    this.router.navigate(['notes']);
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
            defaultImg: 'assets/img/icons-menu/notes.svg',
            backImg
        };
    }
}
