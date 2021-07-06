import { Diseases } from './Diseases';

export interface Medications {
    date: string;
    hour: string;
    locale: string;
    note: string;
    image: string;
    diseases: Array<Diseases>;
    filter: string;
    show: boolean;
}
