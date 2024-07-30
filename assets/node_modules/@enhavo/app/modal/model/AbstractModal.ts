import { ModalInterface } from "@enhavo/app/modal/ModalInterface";
import { ModalManager } from "@enhavo/app/modal/ModalManager";

export abstract class AbstractModal implements ModalInterface
{
    component: string;
    model: string;

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