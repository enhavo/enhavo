import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import FilterInterface from "@enhavo/app/Grid/Filter/FilterInterface";
import * as _ from "lodash";

export default abstract class AbstractFactory
{
    protected application: ApplicationInterface;

    constructor(application: ApplicationInterface)
    {
        this.application = application;
    }

    createFromData(data: object): FilterInterface
    {
        let filter = this.createNew();
        filter = <FilterInterface>_.extend(data, filter);
        return filter;
    }

    abstract createNew(): FilterInterface;
}