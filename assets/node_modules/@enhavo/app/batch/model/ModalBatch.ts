import {AbstractBatch} from "@enhavo/app/batch/model/AbstractBatch";
import ModalManager from "@enhavo/app/modal/ModalManager";

export class ModalBatch extends AbstractBatch
{
    public modal: any;
    public provideData: boolean;
    public provideKey: string;

    private readonly modalManager: ModalManager;

    public constructor(modalManager: ModalManager) {
        super();
        this.modalManager = modalManager;
    }

    async execute(ids: number[]): Promise<boolean>
    {
        if (this.provideData) {
            this.modal[this.provideKey ? this.provideKey : 'data'] = {
                ids: ids,
                type: this.key
            }
        }
        this.modalManager.push(this.modal);
        return false;
    }
}
