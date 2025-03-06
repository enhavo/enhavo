import {Form} from "@enhavo/vue-form/model/Form"
import {ChangeEvent} from "@enhavo/vue-form/event/ChangeEvent";
import {RadioForm} from "@enhavo/vue-form/model/RadioForm";
import {CheckboxForm} from "@enhavo/vue-form/model/CheckboxForm";

export class ChoiceForm extends Form
{
    declare element: HTMLInputElement;
    expanded: boolean;
    placeholderInChoices: boolean;
    multiple: boolean;
    preferredChoices: Choice[];
    separator: string;
    placeholder: string;
    // if expanded is false we got a select box, so choices are needed,
    // otherwise we got children of checkboxes or radios
    choices: Choice[];

    init()
    {
        if (!this.multiple && this.expanded) {
            for (let child of this.children) {
                this.eventDispatcher.on('change', (event) => {
                    for (let sibling of (event as ChangeEvent).form.parent.children) {
                        if (sibling != (event as ChangeEvent).form) {
                            (<RadioForm>sibling).checked = false;
                        }
                    }
                }, child);
            }
        }
    }

    public getModelValue(): any
    {
        if (this.expanded) {
            if (this.multiple) {
                let values = [];
                for (let child of this.children ) {
                    if ((child as CheckboxForm).checked) {
                        values.push(child.getModelValue());
                    }
                }
                return values;
            } else {
                for (let child of this.children) {
                    if ((child as RadioForm).checked) {
                        return child.getModelValue();
                    }
                }
            }
        } else {
            return this.value;
        }

        return null;
    }

    public getFlattedChoices(): Choice[]
    {
        return this.getFlattedChoicesRecursive(this.choices);
    }

    private getFlattedChoicesRecursive(choices: Choice[]): Choice[]
    {
        const returnChoices = [];
        for (let choice of choices) {
            returnChoices.push(choice);
        }

        for (let choice of choices) {
            let flattenChoices = this.getFlattedChoicesRecursive(choice.choices);
            for (let flatChoice of flattenChoices) {
                returnChoices.push(flatChoice);
            }

            returnChoices.push(choice);
        }

        return returnChoices;
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
            super.update(false);
            if (this.multiple) {
                this.fullName = this.fullName + '[]';
            }
        }
    }
}

export class Choice
{
    element: HTMLOptionElement|HTMLOptGroupElement;
    label: string;
    attr: object;
    value: string;
    choices: Choice[];
}
