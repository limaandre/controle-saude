export interface User {
    uid: string;
    displayName: string;
    photoURL: string;
    email: string;
    phoneNumber: string;
    providerId: string;
    bloodType?: string;
    gender?: string;
    birthDate?: string;
    active?: boolean;
}
