import AbstractFactory from "@enhavo/app/Grid/Batch/Factory/AbstractFactory";
import UrlBatch from "@enhavo/app/Grid/Batch/Model/UrlBatch";

export default class UrlFactory extends AbstractFactory
{
    createNew(): UrlBatch {
        return new UrlBatch(this.application, this.application.getDataLoader().load().grid);
    }
}
