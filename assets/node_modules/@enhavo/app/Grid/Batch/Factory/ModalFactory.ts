import AbstractFactory from "@enhavo/app/Grid/Batch/Factory/AbstractFactory";
import ModalBatch from "@enhavo/app/Grid/Batch/Model/ModalBatch";

export default class ModalFactory extends AbstractFactory
{
    createNew(): ModalBatch {
        return new ModalBatch(this.application);
    }
}
