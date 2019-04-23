import SaveAction from "@enhavo/app/Action/Model/SaveAction";
import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";

export default class SaveActionFactory extends AbstractFactory
{
    createNew(): SaveAction {
        return new SaveAction(this.application);
    }
}