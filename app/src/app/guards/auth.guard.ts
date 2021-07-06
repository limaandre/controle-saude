import { Injectable, NgZone } from '@angular/core';
import { CanActivate, Router } from '@angular/router';
import { AngularFireAuth } from '@angular/fire/auth';

@Injectable({
    providedIn: 'root'
})
export class AuthGuard implements CanActivate {

    constructor(
        private zone: NgZone,
        private AFAuth: AngularFireAuth,
        private router: Router
    ) { }

    canActivate(): Promise<boolean> {
        return new Promise(resolve => {
            this.AFAuth.onAuthStateChanged(user => {
                if (!user) {
                    this.zone.run(() => {
                        this.router.navigate(['login']);
                    });
                }
                resolve(user ? true : false);
            });
        });
    }
}
