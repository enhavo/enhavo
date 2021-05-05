import AbstractFilter from "@enhavo/app/grid/filter/model/AbstractFilter";

export default class BetweenFilter extends AbstractFilter
{
    value: Between;
    labelFrom: string;
    labelTo: string;

    reset() {
        this.value.from = this.initialValue;
        this.value.to = this.initialValue;
    }
}

class Between
{
    public from: any;
    public to: any;
}