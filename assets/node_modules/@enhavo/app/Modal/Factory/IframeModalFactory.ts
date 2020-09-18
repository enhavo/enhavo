import AbstractFactory from "@enhavo/app/Modal/Factory/AbstractFactory";
import IframeModal from "@enhavo/app/Modal/Model/IframeModal";
import ModalManager from "@enhavo/app/Modal/ModalManager";

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