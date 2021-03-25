import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";
import ModalAction from "@enhavo/app/Action/Model/ModalAction";
import ModalManager from "@enhavo/app/Modal/ModalManager";

export default class ModalActionFactory extends AbstractFactory
{
    private modalManager: ModalManager;

    constructor(modalManager: ModalManager) {
        super();
        this.modalManager = modalManager;
    }

    createNew(): ModalAction {
        return new ModalAction(this.modalManager);
    }
}