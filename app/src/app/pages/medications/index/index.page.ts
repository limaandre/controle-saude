import { DataFilterService } from './../../../services/data-filter.service';
import { Doctor } from './../../../interfaces/Doctor';
import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { AppService } from 'src/app/services/app.service';
@Component({
    selector: 'app-index',
    templateUrl: './index.page.html',
    styleUrls: ['./index.page.scss'],
})
export class IndexPage implements OnInit {
    dataFilter: Array<Doctor>;

    constructor(
        private router: Router,
        private appService: AppService,
        private dataFilterService: DataFilterService
    ) { }

    ngOnInit() {
    }

    ionViewWillEnter() {
        this.dataFilterService.clearData();
        this.search('');
    }

    goHome() {
        this.router.navigate(['home']);
    }

    search(textSearch) {
        if ( (textSearch && textSearch.length >= 3) || !textSearch) {
            this.appService.medication({search: textSearch}, 'get').subscribe(async (response: any) => {
                if (response.data) {
                    this.dataFilter = this.dataFilterService.setData(response.data);
                }
            });
        }
    }

}
