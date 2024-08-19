import {AbstractMenuItem} from "@enhavo/app/menu/model/AbstractMenuItem";
import {MenuItemInterface} from "@enhavo/app/menu/MenuItemInterface";

export class ListMenuItem extends AbstractMenuItem
{
    public items: Array<MenuItemInterface>;
    public isOpen: boolean = false;

    children(): Array<MenuItemInterface> {
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
        // return this.getManager().isOpen();
    }

    closeOtherMenus() {
        // let items = this.getManager().getItems();
        // for (let item of items) {
        //     if (item !== this && !this.isChildOf(item)) {
        //         if ((<ListMenuItem>item).close) {
        //             (<ListMenuItem>item).close();
        //         }
        //     }
        // }
    }

    private isChildOf(item: MenuItemInterface)
    {
        let children = item.children();
        for (let child of children) {
            if (child == this) {
                return true;
            }
        }
        return false;
    }

    isActive(): boolean {
        return false;
    }
}
