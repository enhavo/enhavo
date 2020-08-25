import AbstractFactory from "@enhavo/app/Grid/Batch/Factory/AbstractFactory";
import ModalBatch from "@enhavo/app/Grid/Batch/Model/ModalBatch";
import ModalManager from "@enhavo/app/Modal/ModalManager";

export default class ModalFactory extends AbstractFactory
{
    private readonly modalManager: ModalManager;

    constructor(modalManager: ModalManager) {
        super();
        this.modalManager = modalManager;
    }

    createNew(): ModalBatch {
        return new ModalBatch(this.modalManager);
    }
}
