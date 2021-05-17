import {FormData} from "@enhavo/vue-form/data/FormData"

export class ChoiceFormData extends FormData
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

