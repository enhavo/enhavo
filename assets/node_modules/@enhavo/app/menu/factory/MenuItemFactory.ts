import AbstractFactory from "@enhavo/app/menu/factory/AbstractFactory";
import MenuItem from "@enhavo/app/menu/model/MenuItem";
import * as _ from "lodash";

export default class MenuDropdownFactory extends AbstractFactory
{
    createFromData(data: object): MenuItem
    {
        return _.extend(data, this.createNew());
    }

    createNew(): MenuItem {
        return new MenuItem(this.eventDispatcher, this.menuManager);
    }
}
