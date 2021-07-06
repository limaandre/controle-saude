import { Component, OnInit, Output, EventEmitter, Input } from '@angular/core';

import { Camera, CameraOptions, PictureSourceType } from '@ionic-native/camera/ngx';
import { ActionSheetController, ToastController, Platform } from '@ionic/angular';
import { File, FileEntry } from '@ionic-native/file/ngx';
import { WebView } from '@ionic-native/ionic-webview/ngx';
import { FilePath } from '@ionic-native/file-path/ngx';

import { AppService } from '../../services/app.service';
import { LanguageService } from '../../services/language.service';

@Component({
    selector: 'app-upload-image',
    templateUrl: './upload-image.component.html',
    styleUrls: ['./upload-image.component.scss'],
})
export class UploadImageComponent implements OnInit {

    @Output() imagem = new EventEmitter();
    @Input() module;

    filesT: any;
    ehMobile = true;
    // module = 'User';

    constructor(
        private appService: AppService,
        private camera: Camera,
        private file: File,
        private webview: WebView,
        private actionSheetController: ActionSheetController,
        private toastController: ToastController,
        private plt: Platform,
        private filePath: FilePath,
        private languageService: LanguageService,
    ) { }

    ngOnInit() {
        if (this.plt.is('desktop') || this.plt.is('mobileweb')) {
            this.ehMobile = false;
        }
    }

    pathForImage(img) {
        if (img === null) {
            return '';
        } else {
            const converted = this.webview.convertFileSrc(img);
            return converted;
        }
    }

    async presentToast(text) {
        const toast = await this.toastController.create({
            message: text,
            position: 'bottom',
            duration: 3000
        });
        toast.present();
    }

    selectImage() {
        this.languageService.getTextTranslate().subscribe(async value => {
            const actionSheet = await this.actionSheetController.create({
                header: value['UPLOAD-IMAGE-COMPONENT'].uploadSelectImage,
                buttons: [{
                    text: value['UPLOAD-IMAGE-COMPONENT'].uploadGallery,
                    handler: () => {
                        this.takePicture(this.camera.PictureSourceType.PHOTOLIBRARY);
                    }
                },
                {
                    text: value['UPLOAD-IMAGE-COMPONENT'].uploadCamera,
                    handler: () => {
                        this.takePicture(this.camera.PictureSourceType.CAMERA);
                    }
                },
                {
                    text: value['UPLOAD-IMAGE-COMPONENT'].uploadCancel,
                    role: 'cancel'
                }
                ]
            });
            await actionSheet.present();
        });

    }

    takePicture(sourceType: PictureSourceType) {
        const options: CameraOptions = {
            quality: 100,
            sourceType,
            saveToPhotoAlbum: false,
            correctOrientation: true
        };

        this.camera.getPicture(options).then(imagePath => {
            if (this.plt.is('android') && sourceType === this.camera.PictureSourceType.PHOTOLIBRARY) {
                this.filePath.resolveNativePath(imagePath)
                    .then(filePath => {
                        const correcPath = filePath.substr(0, filePath.lastIndexOf('/') + 1);
                        const currenName = imagePath.substring(imagePath.lastIndexOf('/') + 1, imagePath.lastIndexOf('?'));
                        this.copyFileToLocalDir(correcPath, currenName, this.createFileName());
                    });
            } else {
                const currentName = imagePath.substr(imagePath.lastIndexOf('/') + 1);
                const correctPath = imagePath.substr(0, imagePath.lastIndexOf('/') + 1);
                this.copyFileToLocalDir(correctPath, currentName, this.createFileName());
            }
        });

    }

    createFileName() {
        const d = new Date();
        const n = d.getTime();
        const newFileName = n + '.jpg';
        return newFileName;
    }

    copyFileToLocalDir(namePath, currentName, newFileName) {
        this.file.copyFile(namePath, currentName, this.file.dataDirectory, newFileName).then(success => {
            this.updateStoredImages(newFileName);
        }, error => {
            this.presentToast('Ocorreu um erro ao buscar a imagem.');
        });
    }

    updateStoredImages(name) {
        const filePath = this.file.dataDirectory + name;
        const resPath = this.pathForImage(filePath);

        const newEntry = {
            name,
            path: resPath,
            filePath
        };

        this.startUpload(newEntry);
    }

    startUpload(imgEntry) {
        this.file.resolveLocalFilesystemUrl(imgEntry.filePath)
            .then(entry => {
                (entry as FileEntry).file(file => this.readFile(file));
            })
            .catch(err => {
                this.presentToast('Ocorreu um erro ao carregar a imagem.');
            });
    }

    readFile(file) {
        const reader = new FileReader();
        reader.onload = () => {
            const formData = new FormData();
            const imgBlob = new Blob([reader.result], {
                type: file.type
            });
            formData.append('file', imgBlob, file.name);
            this.appService.uploadMobile(formData, this.module)
                .subscribe((response: any) => {
                    this.imagem.emit({ imagem: response.data.url });
                });
        };
        reader.readAsArrayBuffer(file);
    }

    /* NAVEGADOR */

    onChange(event) {
        const selectedFiles = event.srcElement.files as FileList;
        const fileNames = [];
        this.filesT = new Set();
        for (const i in selectedFiles) {
            if (Object.prototype.hasOwnProperty.call(selectedFiles, i)) {
                fileNames.push(selectedFiles[i]);
                this.filesT.add(selectedFiles[i]);
            }
        }
        this.appService.uploadWeb(this.filesT, this.module)
            .subscribe((response: any) => {
                this.imagem.emit({ imagem: response.data.url });
            });
    }

    clickInputFile() {
        document.getElementById('customFile').click();
    }

}
