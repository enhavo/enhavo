import DeleteAction from "@enhavo/app/Action/Model/DeleteAction";

export default class DeleteActionFactory
{
    createFromData(data: object): DeleteAction
    {
        let action = this.createNew();
        let object = <DeleteAction>data;
        action.component = object.component;
        return action;
    }

    createNew(): DeleteAction {
        return new DeleteAction()
    }
}