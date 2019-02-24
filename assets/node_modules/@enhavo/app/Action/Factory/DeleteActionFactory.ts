import DeleteAction from "@enhavo/app/Action/Model/DeleteAction";
import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";

export default class DeleteActionFactory extends AbstractFactory
{
    createFromData(data: object): DeleteAction
    {
        let action = this.createNew();
        let object = <DeleteAction>data;
        action.component = object.component;
        return action;
    }

    createNew(): DeleteAction {
        return new DeleteAction(this.application);
    }
}