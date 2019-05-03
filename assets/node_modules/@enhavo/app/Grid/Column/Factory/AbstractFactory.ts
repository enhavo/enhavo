import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import * as _ from "lodash";
import ColumnInterface from "@enhavo/app/Grid/Column/ColumnInterface";

export default abstract class AbstractFactory
{
    protected application: ApplicationInterface;

    constructor(application: ApplicationInterface)
    {
        this.application = application;
    }

    createFromData(data: object): ColumnInterface
    {
        let column = this.createNew();
        column = _.extend(data, column);
        return column;
    }

    abstract createNew(): ColumnInterface;
}