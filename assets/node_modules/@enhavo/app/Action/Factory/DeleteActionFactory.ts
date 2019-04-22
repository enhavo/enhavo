import DeleteAction from "@enhavo/app/Action/Model/DeleteAction";
import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";

export default class DeleteActionFactory extends AbstractFactory
{
    createNew(): DeleteAction {
        return new DeleteAction(this.application);
    }
}