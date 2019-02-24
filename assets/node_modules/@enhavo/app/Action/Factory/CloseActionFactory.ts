import CloseAction from "@enhavo/app/Action/Model/CloseAction";
import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";

export default class CloseActionFactory extends AbstractFactory
{
    createFromData(data: object): CloseAction
    {
        let action = this.createNew();
        let object = <CloseAction>data;
        action.component = object.component;
        return action;
    }

    createNew(): CloseAction {
        return new CloseAction(this.application)
    }
}