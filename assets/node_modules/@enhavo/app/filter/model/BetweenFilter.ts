import { AbstractFilter } from "@enhavo/app/filter/model/AbstractFilter";

export class BetweenFilter extends AbstractFilter
{
    value: Between;
    initialValue: Between;
    labelFrom: string;
    labelTo: string;

    reset() {
        this.value.from = this.initialValue.from;
        this.value.to = this.initialValue.to;
    }
}

class Between
{
    public from: any;
    public to: any;
}