import FilterInterface from "@enhavo/app/grid/filter/FilterInterface";

export default abstract class AbstractFilter implements FilterInterface
{
    component: string;
    value: any;
    key: string;
    label: string;
    initialValue: any;
    active: boolean;

    getValue() {
        return this.value;
    }

    getKey() {
        return this.key;
    }

    getLabel() {
        return this.label;
    }

    setActive(active: boolean) {
        this.active = active;
    }

    getActive() {
        return this.active;
    }

    reset() {
        this.value = this.initialValue;
    }
}