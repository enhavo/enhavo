import OptionFilter from "@enhavo/app/grid/filter/model/OptionFilter";

export default class AutoCompleteEntityFilter extends OptionFilter
{
    url: string;
    minimumInputLength: number;

    reset() {
        this.value = this.initialValue === null ? null : this.initialValue.code;
        this.selected = this.initialValue;
    }
}