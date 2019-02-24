import DropdownAction from "@enhavo/app/Action/Model/DropdownAction";
import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";

export default class DropdownActionFactory extends AbstractFactory
{
    createFromData(data: object): DropdownAction
    {
        let action = this.createNew();
        let object = <DropdownAction>data;
        action.component = object.component;
        return action;
    }

    createNew(): DropdownAction {
        return new DropdownAction(this.application);
    }
}