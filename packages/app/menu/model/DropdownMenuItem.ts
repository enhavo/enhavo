import {AbstractMenuItem} from "@enhavo/app/menu/model/AbstractMenuItem";

export class DropdownMenuItem extends AbstractMenuItem
{
    public value: string;
    public choices: Choice[];
    public selectedValue: any;

    change(value: any)
    {
        if (value == null) {
            this.value = null;
        } else {
            this.value = value.code;
        }
        this.execute(this.value);
    }

    execute(value: string)
    {

    }

    isActive(): boolean
    {
        return false;
    }
}


export class Choice
{
    label: string;
    code: string;
}
