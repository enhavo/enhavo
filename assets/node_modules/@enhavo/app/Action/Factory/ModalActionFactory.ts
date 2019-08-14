import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";
import ModalAction from "@enhavo/app/Action/Model/ModalAction";

export default class ModalActionFactory extends AbstractFactory
{
    createNew(): ModalAction {
        return new ModalAction(this.application);
    }
}