import DateBetweenFilter from "@enhavo/app/Grid/Filter/Model/DateBetweenFilter";
import AbstractFactory from "@enhavo/app/Grid/Filter/Factory/AbstractFactory";

export default class DateBetweenFactory extends AbstractFactory
{
    createFromData(data: object): DateBetweenFilter
    {
        let filter = this.createNew();
        let object = <DateBetweenFilter>data;
        filter.component = object.component;
        return filter;
    }

    createNew(): DateBetweenFilter {
        return new DateBetweenFilter();
    }
}