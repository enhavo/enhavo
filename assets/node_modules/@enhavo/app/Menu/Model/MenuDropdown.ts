import AbstractMenu from "@enhavo/app/Menu/Model/AbstractMenu";

export default class MenuDropdown extends AbstractMenu
{
    public value: string;

    change(value: any) {
        console.log(value)
    }
}