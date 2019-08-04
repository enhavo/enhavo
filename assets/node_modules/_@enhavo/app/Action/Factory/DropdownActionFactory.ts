import DropdownAction from "@enhavo/app/Action/Model/DropdownAction";
import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";

export default class DropdownActionFactory extends AbstractFactory
{
    createNew(): DropdownAction {
        return new DropdownAction(this.application);
    }
}