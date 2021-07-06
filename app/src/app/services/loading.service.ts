import { Injectable } from '@angular/core';

// import { LoadingController } from '@ionic/angular';
import { LoadingController } from '@ionic/angular';


@Injectable({
    providedIn: 'root'
})
export class LoadingService {

    loading = null;
    countLoading = 0;
    finalizou = false;

    constructor(private loadingController: LoadingController) { }

    async loadingPresent() {
        if (!this.loading) {
            this.loading = await this.loadingController.create({
                showBackdrop: false,
                cssClass: 'loading',
                duration: 3000
            });
            return await this.loading.present();
        }
    }

    loadingDismiss() {
        if (this.loading) {
            this.loading.dismiss().then(() => { this.loading = null; });
        }
    }
}
