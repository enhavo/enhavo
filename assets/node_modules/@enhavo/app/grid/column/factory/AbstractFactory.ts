import * as _ from "lodash";
import ColumnInterface from "@enhavo/app/grid/column/ColumnInterface";

export default abstract class AbstractFactory
{
    createFromData(data: object): ColumnInterface
    {
        let column = this.createNew();
        column = _.extend(column, data);
        return column;
    }

    abstract createNew(): ColumnInterface;
}