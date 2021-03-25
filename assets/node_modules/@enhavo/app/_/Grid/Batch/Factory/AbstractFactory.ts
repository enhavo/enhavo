import * as _ from "lodash";
import BatchInterface from "@enhavo/app/Grid/Batch/BatchInterface";
import BatchFactoryInterface from "@enhavo/app/Grid/Batch/BatchFactoryInterface";

export default abstract class AbstractFactory implements BatchFactoryInterface
{
    createFromData(data: object): BatchInterface
    {
        let batch = this.createNew();
        batch = _.extend(data, batch);
        return batch;
    }

    abstract createNew(): BatchInterface;
}