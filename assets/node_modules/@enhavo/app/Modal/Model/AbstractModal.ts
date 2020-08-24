import ModalInterface from "@enhavo/app/Modal/ModalInterface";
import ModalManager from "@enhavo/app/Modal/ModalManager";

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