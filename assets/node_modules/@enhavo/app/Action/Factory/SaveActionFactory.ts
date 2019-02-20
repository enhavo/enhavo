import SaveAction from "@enhavo/app/Action/Model/SaveAction";

export default class SaveActionFactory
{
    createFromData(data: object): SaveAction
    {
        let action = this.createNew();
        let object = <SaveAction>data;
        action.component = object.component;
        return action;
    }

    createNew(): SaveAction {
        return new SaveAction()
    }
}