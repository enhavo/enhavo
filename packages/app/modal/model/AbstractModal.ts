import {ModalInterface} from "@enhavo/app/modal/ModalInterface";
import {ModalManager} from "@enhavo/app/modal/ModalManager";

export abstract class AbstractModal implements ModalInterface
{
    component: string;
    model: string;
    modalManager: ModalManager;

    close()
    {
        this.modalManager.pop();
    }
}
