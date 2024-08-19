import {MenuItemInterface} from "./MenuItemInterface";
import {MenuItemFactory} from "./MenuItemFactory";

export class MenuManager
{
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
}
