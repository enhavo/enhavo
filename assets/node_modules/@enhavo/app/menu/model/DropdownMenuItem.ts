import {AbstractMenuItem} from "@enhavo/app/menu/model/AbstractMenuItem";

export class DropdownMenuItem extends AbstractMenuItem
{
    public value: string;
    public event: string;
    public selectedValue: any;

    change(value: any) {
        if(value == null) {
            this.value = null;
        } else {
            this.value = value.code;
        }
        $(document).trigger(this.event, [value.code]);
    }

    isActive(): boolean {
        return false;
    }
}
