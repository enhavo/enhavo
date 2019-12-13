import AbstractFactory from "@enhavo/app/Grid/Batch/Factory/AbstractFactory";
import FormBatch from "@enhavo/app/Grid/Batch/Model/FormBatch";

export default class FormFactory extends AbstractFactory
{
    createNew(): FormBatch {
        return new FormBatch(this.application, this.application.getDataLoader().load().grid);;
    }
}
