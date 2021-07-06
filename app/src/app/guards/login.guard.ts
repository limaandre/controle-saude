import { Injectable, NgZone } from '@angular/core';
import { CanActivate, Router } from '@angular/router';
import { AngularFireAuth } from '@angular/fire/auth';
import { AuthService } from './../services/auth.service';

@Injectable({
    providedIn: 'root'
})
export class LoginGuard implements CanActivate {
    constructor(
        private zone: NgZone,
        private AFAuth: AngularFireAuth,
        private router: Router,
        private authservice: AuthService
    ) { }

    canActivate(): Promise<boolean> {
        return new Promise(resolve => {
            this.AFAuth.onAuthStateChanged(user => {
                if (user) {
                    this.authservice.setNewUser();
                    this.authservice.redirectHome = true;
                    setTimeout(() => {
                        this.zone.run(() => { this.router.navigate(['home']); });
                    }, 1000);
                }
                resolve(!user ? true : false);
            });
        });
    }
}
