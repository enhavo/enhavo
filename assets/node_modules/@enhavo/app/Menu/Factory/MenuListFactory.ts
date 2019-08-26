import AbstractFactory from "@enhavo/app/Menu/Factory/AbstractFactory";
import MenuList from "@enhavo/app/Menu/Model/MenuList";
import * as _ from "lodash";
import MenuRegistry from "@enhavo/app/Menu/MenuRegistry";
import MenuAwareApplication from "@enhavo/app/Menu/MenuAwareApplication";

export default class MenuListFactory extends AbstractFactory
{
    createFromData(data: object): MenuList
    {
        let menu = this.createNew();
        menu = _.extend(data, menu);
        for(let i in menu.items) {
            let item = this.getRegistry().getFactory(menu.items[i].component).createFromData(menu.items[i]);
            item.setParent(menu);
        }
        return menu;
    }

    createNew(): MenuList {
        return new MenuList(this.application)
    }

    private getRegistry(): MenuRegistry
    {
        return (<MenuAwareApplication>this.application).getMenuRegistry();
    }
}