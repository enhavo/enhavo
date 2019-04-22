import CreateAction from "@enhavo/app/Action/Model/CreateAction";
import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";

export default class CreateActionFactory extends AbstractFactory
{
    createNew(): CreateAction {
        return new CreateAction(this.application);
    }
}