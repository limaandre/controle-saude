import { Injectable } from '@angular/core';
import { Storage } from '@ionic/storage';
import { StorageData } from './../interfaces/StorageData';
import { User } from './../interfaces/User';

@Injectable({
    providedIn: 'root'
})
export class StorageService {

    private storageName = 'app-minha-saude';
    storageData: StorageData;

    constructor(private storage: Storage) {}

    async setStorageData(storageData: StorageData) {
        await this.storage.set(this.storageName, storageData);
        this.storageData = storageData;
    }

    async getStorageData() {
        if (!this.storageData) {
            await this.storage.get(this.storageName).then( async (storageDataStorage: StorageData) => {
                if (storageDataStorage) {
                    this.storageData = storageDataStorage;
                } else {
                    await this.generateStorageDataDefault();
                }
            });
        }
    }

    async deleteUsuario() {
        this.storageData = null;
        await this.storage.remove(this.storageName);
    }

    async generateStorageDataDefault() {
        const dataDefault: StorageData = {
            hasData: false,
            user: {
                uid: null,
                displayName: null,
                photoURL: null,
                email: null,
                phoneNumber: null,
                providerId: null,
                bloodType: null,
                gender: null,
                birthDate: null,
                active: null,
            }
        };
        await this.setStorageData(dataDefault);
    }

    async updateUserStorageData(newUserData: User, hasData = false) {
        if (!this.storageData) {
            await this.generateStorageDataDefault();
        }
        const newData = Object.assign({}, this.storageData);
        newData.hasData = hasData;
        for (const key in newUserData) {
            if (Object.prototype.hasOwnProperty.call(newUserData, key)) {
                newData.user[key] = newUserData[key];
            }
        }
        await this.setStorageData(newData);
    }
}
