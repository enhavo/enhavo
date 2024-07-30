import {ColumnInterface} from "@enhavo/app/column/ColumnInterface";
import {ModelFactory} from "@enhavo/app/factory/ModelFactory";

export class ColumnFactory extends ModelFactory
{
    createWithData(name: string, data: object): ColumnInterface
    {
        return <ColumnInterface>super.createWithData(name, data)
    }

    createNew(name: string): ColumnInterface
    {
        return <ColumnInterface>super.createNew(name);
    }
}