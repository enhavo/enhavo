import AbstractFactory from "@enhavo/app/grid/batch/factory/AbstractFactory";
import ModalBatch from "@enhavo/app/grid/batch/model/ModalBatch";
import ModalManager from "@enhavo/app/modal/ModalManager";

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
