import {AbstractMenuItem} from "@enhavo/app/menu/model/AbstractMenuItem";
import {MenuItemInterface} from "@enhavo/app/menu/MenuItemInterface";
import {MenuManager} from "../MenuManager";

export class ListMenuItem extends AbstractMenuItem
{
    public children: Array<MenuItemInterface>;
    public icon: string;
    public expanded: boolean = false;

    constructor(
        private menuManager: MenuManager,
    ) {
        super();
    }

    onInit()
    {
        for (let i in this.children) {
            this.children[i] = this.menuManager.createMenuItem(this.children[i]);
        }
    }

    open()
    {
        this.expanded = !this.expanded;
    }

    close()
    {
        this.expanded = false;
    }

    isMainMenuOpen()
    {
        return this.menuManager.menuOpen;
    }

    closeOtherMenus()
    {
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

    isActive(): boolean
    {
        for (let child of this.children) {
            if (child.isActive()) {
                return true;
            }
        }
        return false;
    }
}
