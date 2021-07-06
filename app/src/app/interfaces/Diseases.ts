import { Medications } from './Medications';

export interface Diseases {
    name: string;
    note: string;
    medicine: Array<Medications>;
    filter: string;
    show: boolean;
}
