import { StorageData } from './../../interfaces/StorageData';
import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { StorageService } from './../../services/storage.service';
import { AuthService } from './../../services/auth.service';
import { AppService } from 'src/app/services/app.service';
import { LanguageService } from 'src/app/services/language.service';
import { HomeMenu } from 'src/app/interfaces/HomeMenu';
@Component({
    selector: 'app-home',
    templateUrl: 'home.page.html',
    styleUrls: ['home.page.scss'],
})
export class HomePage implements OnInit {
    user: StorageData;
    redirect = false;
    homeMenu: Array<HomeMenu>;

    constructor(
        private authService: AuthService,
        private storageService: StorageService,
        private appService: AppService,
        private router: Router,
        private languageService: LanguageService,
    ) {
        this.user = this.storageService.storageData;
    }

    ngOnInit() {
        this.generateMenu();
        if (this.storageService.storageData.user.email) {
            this.checkIfHasUserInDatabase(true);
        }
    }

    ionViewWillEnter() {
        let redirect = false;
        if (this.authService.redirectHome) {
            redirect = true;
            this.authService.redirectHome = false;
        }
        this.checkIfHasUserInDatabase(redirect);
    }

    generateMenu() {
        this.homeMenu = [
            {icon: 'assets/img/icons-menu/notes.svg', indexLang: 'HOME.menu.notes', page: 'notes'},
            {icon: 'assets/img/icons-menu/medications.svg', indexLang: 'HOME.menu.medications', page: 'medications'},
            {icon: 'assets/img/icons-menu/disease.svg', indexLang: 'HOME.menu.diseases', page: 'diseases'},
            {icon: 'assets/img/icons-menu/doctor.svg', indexLang: 'HOME.menu.professionals', page: 'professionals'},
            {icon: 'assets/img/icons-menu/exams.svg', indexLang: 'HOME.menu.exams', page: 'exams'},
            {icon: 'assets/img/icons-menu/consults.svg', indexLang: 'HOME.menu.consults', page: 'consults'},
        ];
    }

    async checkIfHasUserInDatabase(redirect) {
        const storageData = this.storageService.storageData;
        if (!storageData.user?.email) {
            this.logout();
        }
        this.appService.getUserByEmail(storageData.user.email).subscribe((response: any) => {
            if (response.status) {
                if (response.data.user) {
                    this.storageService.updateUserStorageData(response.data.user, true);
                } else if (!response.data.user && this.storageService.storageData.hasData && !this.storageService.storageData.user.email) {
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
                } else if (redirect) {
                    this.goPage('new-user');
                }
            }
        });
    }

    logout() {
        this.authService.signOut();
    }

    goPage(page) {
        this.router.navigate([page]);
    }
}
