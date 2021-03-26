import DropdownAction from "@enhavo/app/action/model/DropdownAction";
import AbstractFactory from "@enhavo/app/action/factory/AbstractFactory";

export default class DropdownActionFactory extends AbstractFactory
{
    createNew(): DropdownAction {
        return new DropdownAction();
    }
}