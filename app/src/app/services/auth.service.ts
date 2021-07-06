import { Router } from '@angular/router';
import { GooglePlus } from '@ionic-native/google-plus/ngx';
import { Platform, ToastController } from '@ionic/angular';
import { Injectable } from '@angular/core';
import { AngularFireAuth } from '@angular/fire/auth';
import { firebase } from '@firebase/app';
import '@firebase/auth';
import { Facebook, FacebookLoginResponse } from '@ionic-native/facebook/ngx';
import { StorageService } from './storage.service';
import { LanguageService } from './language.service';

@Injectable({
    providedIn: 'root'
})
export class AuthService {
    redirectHome = false;
    constructor(
        private AFAuth: AngularFireAuth,
        private platform: Platform,
        public toastController: ToastController,
        private google: GooglePlus,
        private fb: Facebook,
        private languageService: LanguageService,
        private storageService: StorageService
    ) { }

    async presentToast(msg: string) {
        const toast = await this.toastController.create({
            message: msg,
            duration: 5000
        });
        toast.present();
    }

    loginWithGoogle() {
        if (this.platform.is('cordova')) {
            this.google.login({})
                .then(res => this.nativeGoogleLogin(res))
                .catch(error => this.presentToast(error));
        } else {
            this.webGoogleLogin();
        }
    }

    async webGoogleLogin() {
        try {
            const provider = new firebase.auth.GoogleAuthProvider();
            await this.AFAuth.signInWithPopup(provider);
        } catch (error) {
            this.presentToast(error);
        }
    }

    async nativeGoogleLogin(responseUserLogin) {
        try {
            const provider = firebase.auth.GoogleAuthProvider.credential(null, responseUserLogin.accessToken);
            await this.AFAuth.signInWithCredential(provider);
        } catch (error) {
            this.presentToast(error);
        }
    }

    signOut() {
        this.storageService.deleteUsuario();
        this.AFAuth.signOut();
        if (this.platform.is('cordova')) {
            this.google.logout();
            this.fb.logout();
        }
    }

    loginWithFacebook() {
        if (this.platform.is('cordova')) {
            this.nativeFacebookLogin();
        } else {
            this.webFacebookLogin();
        }
    }

    webFacebookLogin() {
        try {
            this.AFAuth.signInWithPopup(new firebase.auth.FacebookAuthProvider());
        } catch (error) {
            this.presentToast(error);
        }
    }


    nativeFacebookLogin() {
        this.fb.login(['email', 'public_profile'])
            .then((response: FacebookLoginResponse) => {
                const userToken = response.authResponse.accessToken;
                if (response.status === 'connected') {
                    const credentialFB = firebase.auth.FacebookAuthProvider.credential(userToken);
                    this.AFAuth.signInWithCredential(credentialFB);
                }
            });
    }

    setNewUser() {
        if (!this.storageService.storageData || ( this.storageService.storageData && !this.storageService.storageData.user.email)) {
            this.AFAuth.authState.subscribe(async authState => {
                if (authState) {
                    await this.storageService.updateUserStorageData(authState.providerData[0]);
                }
            });
        }
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
                        this.signOut();
                    }
                }];
            })
        };
        this.languageService.showAlertInApp(alert);
    }
}
