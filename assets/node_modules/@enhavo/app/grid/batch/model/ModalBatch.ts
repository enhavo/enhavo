import AbstractBatch from "@enhavo/app/Grid/Batch/Model/AbstractBatch";
import ModalManager from "@enhavo/app/Modal/ModalManager";

export default class ModalBatch extends AbstractBatch
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
        if(this.provideData) {
            this.modal[this.provideKey ? this.provideKey : 'data'] = {
                ids: ids,
                type: this.key
            }
        }
        this.modalManager.push(this.modal);
        return false;
    }
}
