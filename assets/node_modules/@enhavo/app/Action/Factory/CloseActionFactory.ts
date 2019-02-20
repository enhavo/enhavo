import CloseAction from "@enhavo/app/Action/Model/CloseAction";

export default class CloseActionFactory
{
    createFromData(data: object): CloseAction
    {
        let action = this.createNew();
        let object = <CloseAction>data;
        action.component = object.component;
        return action;
    }

    createNew(): CloseAction {
        return new CloseAction()
    }
}