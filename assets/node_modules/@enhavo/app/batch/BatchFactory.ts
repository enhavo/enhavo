import {BatchInterface} from "./BatchInterface";
import {ModelFactory} from "@enhavo/app/factory/ModelFactory";

export class BatchFactory extends ModelFactory
{
    createWithData(name: string, data: object): BatchInterface
    {
        return <BatchInterface>super.createWithData(name, data)
    }

    createNew(name: string): BatchInterface
    {
        return <BatchInterface>super.createNew(name);
    }
}