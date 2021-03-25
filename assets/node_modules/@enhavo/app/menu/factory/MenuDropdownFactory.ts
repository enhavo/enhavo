import AbstractFactory from "@enhavo/app/menu/factory/AbstractFactory";
import MenuDropdown from "@enhavo/app/menu/model/MenuDropdown";
import * as _ from "lodash";

export default class MenuDropdownFactory extends AbstractFactory
{
    createFromData(data: object): MenuDropdown
    {
        return _.extend(data, this.createNew());
    }

    createNew(): MenuDropdown {
        return new MenuDropdown(this.eventDispatcher, this.menuManager);
    }
}