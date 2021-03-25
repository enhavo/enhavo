import DateBetweenFilter from "@enhavo/app/grid/filter/model/DateBetweenFilter";
import AbstractFactory from "@enhavo/app/grid/filter/factory/AbstractFactory";

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