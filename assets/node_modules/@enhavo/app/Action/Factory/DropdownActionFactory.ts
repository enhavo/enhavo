import DropdownAction from "@enhavo/app/Action/Model/DropdownAction";

export default class DropdownActionFactory
{
    createFromData(data: object): DropdownAction
    {
        let action = this.createNew();
        let object = <DropdownAction>data;
        action.component = object.component;
        return action;
    }

    createNew(): DropdownAction {
        return new DropdownAction()
    }
}