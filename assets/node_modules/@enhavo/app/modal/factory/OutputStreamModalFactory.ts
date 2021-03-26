import AbstractFactory from "@enhavo/app/modal/factory/AbstractFactory";
import OutputStreamModal from "@enhavo/app/modal/model/OutputStreamModal";
import ModalManager from "@enhavo/app/modal/ModalManager";

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