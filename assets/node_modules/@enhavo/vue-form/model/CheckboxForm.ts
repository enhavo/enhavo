import {Form} from "@enhavo/vue-form/model/Form"

export class CheckboxForm extends Form
{
    declare element: HTMLInputElement;
    checked: boolean;

    getValue(): any
    {
        return this.checked ? this.value : null;
    }
}
