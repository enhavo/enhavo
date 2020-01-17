import AbstractFilter from "@enhavo/app/Grid/Filter/Model/AbstractFilter";

export default class BetweenFilter extends AbstractFilter
{
    value: Between;

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