import AbstractFactory from "@enhavo/app/menu/factory/AbstractFactory";
import MenuList from "@enhavo/app/menu/model/MenuList";
import * as _ from "lodash";
import MenuRegistry from "@enhavo/app/menu/MenuRegistry";
import EventDispatcher from "@enhavo/app/view-stack/EventDispatcher";
import MenuManager from "@enhavo/app/menu/MenuManager";

export default class MenuListFactory extends AbstractFactory
{
    private menuRegistry: MenuRegistry;

    constructor(eventDispatcher: EventDispatcher, menuManager: MenuManager, menuRegistry: MenuRegistry)
    {
        super(eventDispatcher, menuManager);
        this.menuRegistry = menuRegistry;
    }

    createFromData(data: object): MenuList
    {
        let menu = <MenuList>super.createFromData(data)
        for(let i in menu.items) {
            menu.items[i] = this.menuRegistry.getFactory(menu.items[i].component).createFromData(menu.items[i]);
            menu.items[i].setParent(menu);
        }
        return menu;
    }

    createNew(): MenuList {
        return new MenuList(this.eventDispatcher, this.menuManager)
    }
}
