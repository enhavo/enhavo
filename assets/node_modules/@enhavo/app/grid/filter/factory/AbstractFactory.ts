import FilterInterface from "@enhavo/app/grid/filter/FilterInterface";
import * as _ from "lodash";

export default abstract class AbstractFactory
{
    createFromData(data: object): FilterInterface
    {
        let filter = this.createNew();
        filter = <FilterInterface>_.extend(filter, data);
        return filter;
    }

    abstract createNew(): FilterInterface;
}