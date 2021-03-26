import AbstractFactory from "@enhavo/app/modal/factory/AbstractFactory";
import IframeModal from "@enhavo/app/modal/model/IframeModal";
import ModalManager from "@enhavo/app/modal/ModalManager";

export default class IframeModalFactory extends AbstractFactory
{
    private readonly modalManager: ModalManager;

    constructor(modalManager: ModalManager) {
        super();
        this.modalManager = modalManager;
    }

    createNew(): IframeModal {
        return new IframeModal(this.modalManager);
    }
}