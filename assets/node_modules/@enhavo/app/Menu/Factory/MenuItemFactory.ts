import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";
import MenuItem from "@enhavo/app/Menu/Model/MenuItem";
import * as _ from "lodash";

export default class MenuDropdownFactory extends AbstractFactory
{
    createFromData(data: object): MenuItem
    {
        return _.extend(data, this.createNew());
    }

    createNew(): MenuItem {
        return new MenuItem(this.application)
    }
}