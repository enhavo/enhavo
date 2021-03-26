import AbstractMenu from "@enhavo/app/menu/model/AbstractMenu";

export default class MenuDropdown extends AbstractMenu
{
    public value: string;
    public event: string;

    change(value: any) {
        this.value = value.label;
        $(document).trigger(this.event, [value.code]);
    }
}