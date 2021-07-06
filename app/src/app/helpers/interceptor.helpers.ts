// import { OneSignal } from '@ionic-native/onesignal/ngx';
import { Injectable } from '@angular/core';
import { HttpEvent, HttpInterceptor, HttpHandler, HttpRequest, HttpResponse, HttpHeaders } from '@angular/common/http';
import { Router } from '@angular/router';
import { LoadingController, AlertController, Platform } from '@ionic/angular';

import { Observable, from } from 'rxjs';
import { map, catchError, finalize, mergeMap } from 'rxjs/operators';

import { StorageService } from './../services/storage.service';
import { LoadingService } from './../services/loading.service';
import { LanguageService } from './../services/language.service';
import { RenderError } from './../interfaces/RenderError';

@Injectable()
export class InterceptorHelpers implements HttpInterceptor {
    usuario;
    countAlert = 0;
    ehMobile = true;
    appVersion = '1';
    provider = '';
    userEmail = '';

    constructor(
        private storageService: StorageService,
        private loadingService: LoadingService,
        public loadingController: LoadingController,
        private alertController: AlertController,
        private plt: Platform,
        private router: Router,
        private languageService: LanguageService
    ) {
        // this.usuario = storageService.usuario;
        if (this.plt.is('desktop') || this.plt.is('mobileweb')) {
            this.ehMobile = false;
        }
    }

    intercept(request: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
        const execRequest = this.execRequest(request, next);
        return from(execRequest).pipe(
            mergeMap((req: Observable<HttpEvent<any>>) => {
                return req;
            })
        );
    }

    async execRequest(request: HttpRequest<any>, next: HttpHandler): Promise<any> {
        let async = false;
        if (request.url.includes('async=true')) {
            async = true;
        }

        if (next && !async) {
            await this.loadingService.loadingPresent();
        }

        if (this.storageService.storageData) {
           this.provider = this.storageService.storageData.user.providerId;
           this.userEmail = this.storageService.storageData.user.email;
        }

        let headers = new HttpHeaders();
        if (!request.url.includes('upload_imagem')) {
            headers = new HttpHeaders()
            .append('X-Apikey', 'g5dKMw2w4KZapcHujSO4RyPwufp0lY3HTWSgHKSBWrcBKjwu3B')
            .append('Client', 'minha-saude')
            .append('Content-Type', 'application/json')
            .append('App', this.ehMobile.toString())
            .append('Versao', this.appVersion)
            .append('Provider', this.provider ? this.provider : '')
            .append('UserEmail', this.userEmail ? this.userEmail : '')
            .append('Language', this.languageService.selected);
        }
        request = request.clone({ headers });
        if (this.ehMobile && this.usuario) {
            const { token, matricula } = this.usuario;
            const versao = this.appVersion;
        }
        return this.handler(next, request, async);
    }

    handler(next, request, async) {
        return next.handle(request).pipe(
            map((event: HttpEvent<any>) => {
                if (event instanceof HttpResponse) {
                    if (!event.body.status && !async) {
                        this.trataError(event);
                    }
                    return event;
                }
            }),
            catchError(response => {
                if (!async) {
                    if (response.error && !response.error.status) {
                        const errorParams = {
                            msg: response.error.msg,
                            redirect: response.error.redirect ? true : false,
                            page: response.error.redirect ? response.error.redirect : '',
                            headerError: response.error.header ? response.error.header : '',
                        };
                        this.renderError(errorParams);
                        return [];
                    }
                    this.renderError();
                }
                return [];
            }),
            finalize(() => {
                this.loadingService.loadingDismiss();
            })
        );
    }

    private trataError(event: any) {
        let redirect = false;
        let msgError = null;
        let headerError = null;

        if (event.body.msg) {
            msgError = event.body.msg;
        }

        if (event.body.redirect) {
            redirect = true;
        }

        if (event.body.headerError) {
            headerError = event.body.headerError;
        }

        const errorParams = {
            msg: msgError,
            redirect,
            page: event.body.redirect,
            headerError,
        };

        this.renderError(errorParams);
    }

    renderError(params?: RenderError) {
        this.languageService.getTextTranslate().subscribe( async texts => {
            const alertParams = {
                redirect: params.redirect ? params.redirect : null,
                page: params.page ? params.page : null,
                msgError: params.msg ? params.msg : texts.INTERCEPTOR.msgError,
                headerError: params.headerError ? params.headerError : texts.INTERCEPTOR.headerError,
                btnAlert: texts.INTERCEPTOR.btnAlert
            };
            await this.showAlert(alertParams);
        });
    }

    async showAlert(params) {
        const {
            headerError,
            msgError,
            btnAlert,
            redirect,
            page
        } = params;
        if (this.countAlert === 0) {
            this.countAlert++;
            const alert = await this.alertController.create({
                cssClass: 'my-custom-class',
                header: headerError,
                message: msgError,
                buttons: [
                    {
                        text: btnAlert,
                        cssClass: 'primary',
                        handler: async (e) => {
                            this.countAlert = 0;
                            if (redirect) {
                                this.router.navigate([page]);
                            }
                        }
                    }
                ]
            });
            await alert.present();
            this.loadingService.loadingDismiss();
        }
    }
}
