import {OptionFilter} from "@enhavo/app/filter/model/OptionFilter";

export class AutoCompleteEntityFilter extends OptionFilter
{
    url: string;
    minimumInputLength: number;

    reset() {
        this.value = this.initialValue === null ? null : this.initialValue.code;
        this.selected = this.initialValue;
    }
}
