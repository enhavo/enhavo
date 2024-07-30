import {FilterInterface} from "./FilterInterface";
import {ModelFactory} from "@enhavo/app/factory/ModelFactory";

export class FilterFactory extends ModelFactory
{
    createWithData(name: string, data: object): FilterInterface
    {
        return <FilterInterface>super.createWithData(name, data)
    }

    createNew(name: string): FilterInterface
    {
        return <FilterInterface>super.createNew(name);
    }
}