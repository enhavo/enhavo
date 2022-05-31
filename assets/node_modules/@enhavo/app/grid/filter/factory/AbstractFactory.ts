import FilterInterface from "@enhavo/app/grid/filter/FilterInterface";
import * as _ from "lodash";

export default abstract class AbstractFactory
{
    createFromData(data: object): FilterInterface
    {
        let filter = this.createNew();
        return _.assign(filter, data);
    }

    abstract createNew(): FilterInterface;
}