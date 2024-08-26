import {MenuItemInterface} from "./MenuItemInterface";
import {MenuItemFactory} from "./MenuItemFactory";

export class MenuManager
{
    public menuOpen: boolean = true;
    public menuItems: MenuItemInterface[]
    public menuCustomChange: boolean = false;

    constructor(
        private readonly factory: MenuItemFactory,
    ) {
    }

    createMenuItems(items: object[]): MenuItemInterface[]
    {
        let data = [];
        for (let i in items) {
            data.push(this.createMenuItem(items[i]));
        }
        return data;
    }

    createMenuItem(item: object): MenuItemInterface
    {
        if (!item.hasOwnProperty('model')) {
            throw 'The menu item data needs a "model" property!';
        }

        return this.factory.createWithData(item['model'], item);
    }

    setMenuItems(items: MenuItemInterface[])
    {
        this.menuItems = items;
    }

    toggleMenu()
    {
        this.menuOpen = !this.menuOpen;
        this.menuCustomChange = true;
    }

    start()
    {
        if (this.menuItems && this.menuItems.length > 0) {
            for (let item of this.menuItems) {
                if (item.clickable) {
                    item.open();
                    return;
                }
            }
        }
    }

    open()
    {
        this.menuOpen = true;
    }

    close()
    {
        this.menuOpen = false;
    }

    getItems(): Array<MenuItemInterface>
    {
        let items = [];
        for (let item of this.menuItems) {
            items.push(item);
            for (let descendant of item.getDescendants()) {
                items.push(descendant);
            }
        }
        return items;
    }

    isCustomChange(): boolean
    {
        return this.menuCustomChange;
    }
}
