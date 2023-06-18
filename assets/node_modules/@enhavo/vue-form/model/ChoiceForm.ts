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

    init() {

    }

    public update(recursive: boolean = true)
    {
        if (this.expanded) {
            super.update(false);
            let fullName = this.fullName;
            if (this.multiple) {
                fullName += '[]';
            }
            for (let child of this.children) {
                child.fullName = fullName;
            }
        } else {
            super.update(recursive);
        }
    }
}

export class Choice
{
    label: string;
    attr: object;
    value: string;
    choices: Choice[];
}
