import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";
import ModalManager from "@enhavo/app/Modal/ModalManager";

export default class ModalAction extends AbstractAction
{
    public modal: any;

    private modalManager: ModalManager;

    constructor(modalManager: ModalManager) {
        super();
        this.modalManager = modalManager;
    }

    execute(): void
    {
        this.modalManager.push(this.modal);
    }
}
