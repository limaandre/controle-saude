import { Professionals } from './../interfaces/Professionals';
import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { backUrl } from '../../environments/environment';

@Injectable({
    providedIn: 'root'
})
export class AppService {

    urlApi = `${backUrl}api/`;

    constructor(private http: HttpClient) { }

    private request(method: string, url: string, param: object = null) {
        let request = null;

        if (method === 'get') {
            if (param) {
                url += '?' + this.objectToUrl(param);
            }
            request = this.http.get<any>(`${this.urlApi + url}`);
        } else if (method === 'post') {
            request = this.http.post(
                `${this.urlApi + url}`,
                JSON.stringify(param)
            );
        } else if (method === 'put') {
            request = this.http.put(
                `${this.urlApi + url}`,
                JSON.stringify(param)
            );
        } else if (method === 'delete') {
            if (param) {
                url += '?' + this.objectToUrl(param);
            }
            request = this.http.delete(
                `${this.urlApi + url}`
            );
        }

        return request;
    }

    private objectToUrl(object) {
        let str = '';
        for (const key of Object.keys(object)) {
            if (str !== '') {
                str += '&';
            }
            str += key + '=' + encodeURIComponent(object[key]);
        }
        return str;
    }

    doctor(doctor: object, httpType: string): Observable<object> {
        return this.request(httpType, `doctor`, doctor);
    }

    medication(medication: object, httpType: string): Observable<object> {
        return this.request(httpType, `medication`, medication);
    }

    exam(exam: object, httpType: string): Observable<object> {
        return this.request(httpType, `exam`, exam);
    }

    consult(consult: object, httpType: string): Observable<object> {
        return this.request(httpType, `consult`, consult);
    }

    disease(disease: object, httpType: string): Observable<object> {
        return this.request(httpType, `disease`, disease);
    }

    notes(notes: object, httpType: string): Observable<object> {
        return this.request(httpType, `notes`, notes);
    }

    saveUser(user: object, httpType: string): Observable<object> {
        return this.request(httpType, `user`, user);
    }

    getUserByEmail(email: string): Observable<object> {
        return this.request('get', `user`, { email });
    }

    login(user: object): Observable<object> {
        return this.request('post', `login`, user);
    }

    deleteData(data: object): Observable<object> {
        return this.request('delete', `delete_data`, data);
    }

    uploadWeb(files: Set<File>, modulo) {
        const formData = new FormData();
        files.forEach(file => formData.append('file', file, file.name));
        return this.http.post(`${this.urlApi}upload_imagem?modulo=${modulo}`, formData);
    }

    uploadMobile(files: FormData, modulo) {
        return this.http.post(`${this.urlApi}upload_imagem?modulo=${modulo}`, files);
    }
}
