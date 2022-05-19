import * as _ from "lodash";
import BatchInterface from "@enhavo/app/grid/batch/BatchInterface";
import BatchFactoryInterface from "@enhavo/app/grid/batch/BatchFactoryInterface";

export default abstract class AbstractFactory implements BatchFactoryInterface
{
    createFromData(data: object): BatchInterface
    {
        let batch = this.createNew();
        batch = _.extend(batch, data);
        return batch;
    }

    abstract createNew(): BatchInterface;
}