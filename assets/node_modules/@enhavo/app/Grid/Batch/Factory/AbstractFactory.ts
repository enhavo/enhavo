import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import * as _ from "lodash";
import BatchInterface from "@enhavo/app/Grid/Batch/BatchInterface";
import BatchFactoryInterface from "@enhavo/app/Grid/Batch/BatchFactoryInterface";

export default abstract class AbstractFactory implements BatchFactoryInterface
{
    protected application: ApplicationInterface;

    constructor(application: ApplicationInterface)
    {
        this.application = application;
    }

    createFromData(data: object): BatchInterface
    {
        let batch = this.createNew();
        batch = _.extend(data, batch);
        return batch;
    }

    abstract createNew(): BatchInterface;
}