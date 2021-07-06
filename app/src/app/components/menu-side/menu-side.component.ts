import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { MenuController } from '@ionic/angular';
import { AuthService } from 'src/app/services/auth.service';

@Component({
    selector: 'app-menu-side',
    templateUrl: './menu-side.component.html',
    styleUrls: ['./menu-side.component.scss'],
})
export class MenuSideComponent implements OnInit {

    itensMenu = [
        {translate: 'MENU.profile', icon: 'person-circle-outline', func: this.goProfile, params: this},
        {translate: 'MENU.logout', icon: 'log-out-outline', func: this.logout, params: this},
    ];

    constructor(
        private menu: MenuController,
        private authService: AuthService,
        private router: Router
    ) { }

    ngOnInit() { }

    logout(param) {
        param.menu.close();
        param.authService.signOut();
    }

    goProfile(param) {
        param.menu.close();
        param.router.navigate(['new-user']);
    }

}
