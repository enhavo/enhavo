import AbstractFactory from "@enhavo/app/action/factory/AbstractFactory";
import ModalAction from "@enhavo/app/action/model/ModalAction";
import ModalManager from "@enhavo/app/modal/ModalManager";

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