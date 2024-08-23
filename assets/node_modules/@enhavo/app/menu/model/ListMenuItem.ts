import {AbstractMenuItem} from "@enhavo/app/menu/model/AbstractMenuItem";
import {MenuItemInterface} from "@enhavo/app/menu/MenuItemInterface";
import {MenuManager} from "../MenuManager";

export class ListMenuItem extends AbstractMenuItem
{
    public items: Array<MenuItemInterface>;
    public isOpen: boolean = false;
    public icon: string;

    constructor(
        private menuManager: MenuManager,
    ) {
        super();
    }

    onInit() {
        for (let i in this.items) {
            this.items[i] = this.menuManager.createMenuItem(this.items[i]);
        }
    }

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
        return this.menuManager.menuOpen;
    }

    closeOtherMenus() {
        for (let item of this.menuManager.getItems()) {
            if (item !== this && !this.isChildOf(item)) {
                if ((<ListMenuItem>item).close) {
                    (<ListMenuItem>item).close();
                }
            }
        }
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
        for (let child of this.items) {
            if (child.isActive()) {
                return true;
            }
        }
        return false;
    }
}
