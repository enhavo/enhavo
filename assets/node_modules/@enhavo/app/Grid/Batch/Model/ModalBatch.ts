import AbstractBatch from "@enhavo/app/Grid/Batch/Model/AbstractBatch";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";

export default class ModalBatch extends AbstractBatch
{
    public application: ApplicationInterface;
    public modal: string;

    public constructor(application: ApplicationInterface) {
        super();
        this.application = application;
    }

    async execute(ids: number[]): Promise<boolean>
    {
        this.application.getModalManager().push(this.modal, {});
        return false;
    }
}