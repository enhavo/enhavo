import FilterInterface from "@enhavo/app/Grid/Filter/FilterInterface";

export default abstract class AbstractFilter implements FilterInterface
{
    component: string;
    value: any;
    key: string;
    label: string;
    initialValue: string;

    getValue() {
        return this.value;
    }

    getKey() {
        return this.key;
    }

    reset() {
        this.value = this.initialValue;
    }
}