import {Form} from "@enhavo/vue-form/model/Form"

export class ChoiceForm extends Form
{
    expanded: boolean;
    placeholderInChoices: boolean;
    multiple: boolean;
    preferredChoices: Choice[];
    separator: string;
    placeholder: string;
    choices: Choice[];
}

export class Choice
{
    label: string;
    attr: object;
    value: string;
    choices: Choice[];
}

