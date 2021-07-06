import { Doctor } from './Doctor';

export interface Consult {
    date: string;
    hour: string;
    locale: string;
    note: string;
    image: string;
    doctor: Doctor;
    filter: string;
    show: boolean;
}
