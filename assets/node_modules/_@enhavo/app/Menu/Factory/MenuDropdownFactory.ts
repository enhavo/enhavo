import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";
import MenuDropdown from "@enhavo/app/Menu/Model/MenuDropdown";
import * as _ from "lodash";

export default class MenuDropdownFactory extends AbstractFactory
{
    createFromData(data: object): MenuDropdown
    {
        return _.extend(data, this.createNew());
    }

    createNew(): MenuDropdown {
        return new MenuDropdown(this.application)
    }
}