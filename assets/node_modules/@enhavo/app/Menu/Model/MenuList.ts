import AbstractMenu from "@enhavo/app/Menu/Model/AbstractMenu";
import MenuInterface from "@enhavo/app/Menu/MenuInterface";
import MenuAwareApplication from "@enhavo/app/Menu/MenuAwareApplication";
import MenuManager from "@enhavo/app/Menu/MenuManager";

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
        return this.getMenuManager().isOpen();
    }

    closeOtherMenus() {
        let items = this.getMenuManager().getItems();
        for(let item of items) {
            if(item !== this) {
                if((<MenuList>item).close) {
                    (<MenuList>item).close();
                }
            }
        }
    }

    private getMenuManager(): MenuManager {
        return (<MenuAwareApplication>this.application).getMenuManager();
    }
}