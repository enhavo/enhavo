import AbstractBatch from "@enhavo/app/Grid/Batch/Model/AbstractBatch";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";

export default class ModalBatch extends AbstractBatch
{
    public application: ApplicationInterface;
    public modal: any;
    public provideData: boolean;
    public provideKey: string;

    public constructor(application: ApplicationInterface) {
        super();
        this.application = application;
    }

    async execute(ids: number[]): Promise<boolean>
    {
        if(this.provideData) {
            this.modal[this.provideKey ? this.provideKey : 'data'] = {
                ids: ids,
                type: this.key
            }
        }
        this.application.getModalManager().push(this.modal);
        return false;
    }
}