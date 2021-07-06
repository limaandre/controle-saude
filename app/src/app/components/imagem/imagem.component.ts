import { Component, OnInit, Input, OnChanges, OnDestroy } from '@angular/core';

@Component({
    selector: 'app-imagem',
    templateUrl: './imagem.component.html',
    styleUrls: ['./imagem.component.scss'],
})
export class ImagemComponent implements OnInit, OnChanges, OnDestroy {

    loaded = false;
    showImage = true;

    @Input() imagem;
    @Input() classes = '';

    imageComponent = '';

    constructor() { }

    ngOnInit() {
        this.setImage();
    }

    ngOnChanges() {
        this.setImage();
    }

    ngOnDestroy() {
        this.loaded = false;
        this.showImage = true;
        this.imageComponent = '';
    }

    setImage() {
        if (!this.imageComponent || this.imageComponent.includes('assets/img/icons-menu/')) {
            if (this.imagem?.backImg && this.showImage) {
                this.imageComponent = this.imagem?.backImg;
            } else {
                this.imageComponent = this.imagem?.defaultImg;
            }
        }
    }

    erroImg() {
        this.showImage = false;
        this.imageComponent = this.imagem?.defaultImg;
    }

    imageLoaded() {
        this.loaded = true;
    }
}
