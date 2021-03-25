import AbstractFactory from "@enhavo/app/Menu/Factory/AbstractFactory";
import MenuList from "@enhavo/app/Menu/Model/MenuList";
import * as _ from "lodash";
import MenuRegistry from "@enhavo/app/Menu/MenuRegistry";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import MenuManager from "@enhavo/app/Menu/MenuManager";

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
        let menu = this.createNew();
        menu = _.extend(data, menu);
        for(let i in menu.items) {
            let item = this.menuRegistry.getFactory(menu.items[i].component).createFromData(menu.items[i]);
            item.setParent(menu);
        }
        return menu;
    }

    createNew(): MenuList {
        return new MenuList(this.eventDispatcher, this.menuManager)
    }
}
