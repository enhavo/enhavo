import {Form} from "@enhavo/vue-form/model/Form"

export class CheckboxForm extends Form
{
    checked: boolean;
    element: HTMLInputElement;

    getValue(): any
    {
        return this.checked ? this.value : null;
    }
}
