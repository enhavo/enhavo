import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import FilterInterface from "@enhavo/app/Grid/Filter/FilterInterface";

export default abstract class AbstractFilter implements FilterInterface
{
    protected application: ApplicationInterface;
    component: string;
    value: any;
    key: string;
    label: string;
    initialValue: string;

    constructor(application: ApplicationInterface)
    {
        this.application = application;
    }

    getValue() {
        return this.value;
    };

    getKey() {
        return this.key;
    };

    reset() {
        this.value = this.initialValue;
    }
}