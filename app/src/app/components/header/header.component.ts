import { Component, OnInit, Input } from '@angular/core';
import { StorageService } from 'src/app/services/storage.service';
import { User } from './../../interfaces/User';

@Component({
    selector: 'app-header',
    templateUrl: './header.component.html',
    styleUrls: ['./header.component.scss'],
})
export class HeaderComponent implements OnInit {
    @Input() texts: any;

    user: User;

    constructor(
        private storageService: StorageService
    ) {
        this.user = this.storageService.storageData.user;
    }

    ngOnInit() {
        setTimeout(() => { this.othersInfo(); }, 1000);
    }

    parseName() {
        if (this.user && this.user.displayName) {
            const name = this.user.displayName.split(' ');
            if (name.length === 1) {
                return name[0];
            }
            return name[0] + ' ' + name[name.length - 1];
        }
        return '';
    }

    othersInfo() {
        if (this.user && this.user.birthDate) {
            const years = this.calculateYears();
            const bloodType = this.user.bloodType;
            const gender = this.getGender();
            return years + ' ' + this.texts.years + ', ' + bloodType + ', ' + gender;
        }
    }

    getGender() {
        const gender = this.user.gender;
        switch (gender) {
            case 'M':
                return this.texts.genderMale;
            case 'F':
                return this.texts.genderFemale;
            case 'O':
                return this.texts.genderOthers;
        }
    }

    calculateYears() {
        const userYear = this.user.birthDate.replace(' ', 'T');
        const date1 = new Date(userYear);
        const date2 = new Date(Date.now());

        const diff = Math.floor(date1.getTime() - date2.getTime());
        const day = 1000 * 60 * 60 * 24;

        const days = Math.floor(diff / day);
        const months = Math.floor(days / 31);
        const years = Math.floor(months / 12);
        return years < 0 ? years * -1 : years;
    }
}
