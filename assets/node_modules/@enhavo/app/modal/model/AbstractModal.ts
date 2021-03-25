import ModalInterface from "@enhavo/app/modal/ModalInterface";
import ModalManager from "@enhavo/app/modal/ModalManager";

export default abstract class AbstractModal implements ModalInterface
{
    component: string;

    protected readonly modalManager: ModalManager;

    constructor(modalManager: ModalManager)
    {
        this.modalManager = modalManager;
    }

    init() {}

    close() {
        this.modalManager.pop();
    }
}