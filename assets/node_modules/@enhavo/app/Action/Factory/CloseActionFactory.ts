import CloseAction from "@enhavo/app/Action/Model/CloseAction";
import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";

export default class CloseActionFactory extends AbstractFactory
{
    createNew(): CloseAction {
        return new CloseAction(this.application)
    }
}