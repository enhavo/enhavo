import {FilterInterface} from "@enhavo/app/filter/FilterInterface";

export abstract class AbstractFilter implements FilterInterface
{
    component: string;
    model: string;
    value: any;
    key: string;
    label: string;
    initialValue: any;
    active: boolean;

    reset() {
        this.value = this.initialValue;
    }
}
