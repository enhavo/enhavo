import AbstractMenu from "@enhavo/app/Menu/Model/AbstractMenu";
import MenuInterface from "@enhavo/app/Menu/MenuInterface";

export default class MenuList extends AbstractMenu
{
    public items: Array<MenuInterface>;
    public isOpen: boolean = false;

    children(): Array<MenuInterface> {
        return this.items;
    }

    select() {
        super.select();
        this.isOpen = true;
    }

    open() {
        this.isOpen = true;
    }

    close() {
        this.isOpen = false;
    }

    isMainMenuOpen() {
        return this.getManager().isOpen();
    }

    closeOtherMenus() {
        let items = this.getManager().getItems();
        for(let item of items) {
            if(item !== this) {
                if((<MenuList>item).close) {
                    (<MenuList>item).close();
                }
            }
        }
    }
}
