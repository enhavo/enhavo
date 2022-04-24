import AbstractFactory from "@enhavo/app/menu/factory/AbstractFactory";
import MenuItem from "@enhavo/app/menu/model/MenuItem";
import * as _ from "lodash";

export default class MenuDropdownFactory extends AbstractFactory
{
    createNew(): MenuItem {
        return new MenuItem(this.eventDispatcher, this.menuManager);
    }
}
