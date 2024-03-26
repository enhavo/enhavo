import {Form} from "@enhavo/vue-form/model/Form"

export class RadioForm extends Form
{
    checked: boolean;
    element: HTMLInputElement;

    getValue(): any
    {
        return this.checked ? this.value : null;
    }
}
