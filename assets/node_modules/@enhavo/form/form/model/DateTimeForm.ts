import {Form} from "@enhavo/vue-form/model/Form"

export class DateTimeForm extends Form
{
    config: object;
    allowTyping: boolean;
    allowClear: boolean;
    timepicker: boolean;
    locale: boolean;
}
