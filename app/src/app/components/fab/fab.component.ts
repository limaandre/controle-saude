import { LanguageService } from './../../services/language.service';
import { Component, OnInit } from '@angular/core';

@Component({
    selector: 'app-fab',
    templateUrl: './fab.component.html',
    styleUrls: ['./fab.component.scss'],
})
export class FabComponent implements OnInit {
    languages = [];

    constructor(
        public languageSerivce: LanguageService
    ) {}

    ngOnInit() {
        this.initLanguages();
    }

    async initLanguages() {
        this.languages = this.languageSerivce.getLanguages();
    }

    select(lng: string) {
        this.languageSerivce.setLanguage(lng);
    }
}
