import AbstractFactory from "@enhavo/app/Modal/Factory/AbstractFactory";
import OutputStreamModal from "@enhavo/app/Modal/Model/OutputStreamModal";
import ModalManager from "@enhavo/app/Modal/ModalManager";

export default class OutputStreamModalFactory extends AbstractFactory
{
    private readonly modalManager: ModalManager;

    constructor(modalManager: ModalManager) {
        super();
        this.modalManager = modalManager;
    }

    createNew(): OutputStreamModal {
        return new OutputStreamModal(this.modalManager);
    }
}