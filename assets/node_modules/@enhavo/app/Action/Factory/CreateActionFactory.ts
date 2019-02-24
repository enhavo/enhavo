import CreateAction from "@enhavo/app/Action/Model/CreateAction";
import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";

export default class CreateActionFactory extends AbstractFactory
{
    createFromData(data: object): CreateAction
    {
        let action = this.createNew();
        let object = <CreateAction>data;
        action.component = object.component;
        action.label = object.label;
        action.url = object.url;
        return action;
    }

    createNew(): CreateAction {
        return new CreateAction(this.application);
    }
}