import { AbstractFilter } from "@enhavo/app/filter/model/AbstractFilter";

export class OptionFilter extends AbstractFilter
{
    choices: Choice[];
    selected: any;

    reset() {
        this.value = this.initialValue;
        this.selected = null;
        for(let choice of this.choices) {
            if (choice.code == this.value) {
                this.selected = choice;
                break;
            }
        }
    }
}

class Choice {
    code: string;
    label: string;
}
