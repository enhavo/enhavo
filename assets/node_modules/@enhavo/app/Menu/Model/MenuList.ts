import AbstractMenu from "@enhavo/app/Menu/Model/AbstractMenu";
import MenuInterface from "@enhavo/app/Menu/MenuInterface";

export default class MenuList extends AbstractMenu
{
    public items: Array<MenuInterface>;

    children(): Array<MenuInterface> {
        return this.items;
    }

    open() {}

    close() {}
}