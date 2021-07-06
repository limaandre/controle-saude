import { Component, OnInit, ViewChild, Input } from '@angular/core';
import { Router } from '@angular/router';
import { IonInfiniteScroll } from '@ionic/angular';
import { AppService } from 'src/app/services/app.service';
import { DataFilterService } from 'src/app/services/data-filter.service';

@Component({
    selector: 'app-search-data',
    templateUrl: './search-data.component.html',
    styleUrls: ['./search-data.component.scss'],
})
export class SearchDataComponent implements OnInit {
    @Input() searchData: (args: any) => void;
    @Input() type: string;

    @ViewChild(IonInfiniteScroll) infiniteScroll: IonInfiniteScroll;

    constructor(
        private appService: AppService,
        private router: Router,
        public dataFilterService: DataFilterService
    ) { }

    ngOnInit() {}

    onClear(e) {
        this.filterData(null);
    }

    onChange(e) {
        this.filterData(e);
    }

    filterData(e) {
        this.dataFilterService.dataFilter = this.dataFilterService.dataFilter.map(data => {
            data.show = true;
            const dados = Object.values(data).join(', ').toLowerCase();
            if (e && !dados.includes(e.detail.value.toLowerCase())) {
                data.show = false;
            }
            return data;
        });
    }

    newData() {
        this.router.navigate([this.type + '/form']);
    }
}
