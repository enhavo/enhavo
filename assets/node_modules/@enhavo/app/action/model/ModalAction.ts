import { AbstractAction } from "@enhavo/app/action/model/AbstractAction";
import ModalManager from "@enhavo/app/modal/ModalManager";

export class ModalAction extends AbstractAction
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
