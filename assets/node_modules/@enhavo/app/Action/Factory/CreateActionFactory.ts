import CreateAction from "@enhavo/app/Action/Model/CreateAction";

export default class CreateActionFactory
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
        return new CreateAction()
    }
}