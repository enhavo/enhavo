import {Form} from "@enhavo/vue-form/model/Form"
import {ChangeEvent} from "@enhavo/vue-form/event/ChangeEvent";
import {RadioForm} from "@enhavo/vue-form/model/RadioForm";

export class ChoiceForm extends Form
{
    element: HTMLInputElement;
    expanded: boolean;
    placeholderInChoices: boolean;
    multiple: boolean;
    preferredChoices: Choice[];
    separator: string;
    placeholder: string;
    choices: Choice[];

    init()
    {
        if (!this.multiple && this.expanded) {
            for (let child of this.children) {
                this.eventDispatcher.on('change', (event: ChangeEvent) => {
                    for (let sibling of event.form.parent.children) {
                        if (sibling != event.form) {
                            (<RadioForm>sibling).checked = false;
                        }
                    }
                }, child);
            }
        }
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
