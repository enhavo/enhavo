import DateBetweenFilter from "@enhavo/app/Grid/Filter/Model/DateBetweenFilter";
import AbstractFactory from "@enhavo/app/Grid/Filter/Factory/AbstractFactory";

export default class DateBetweenFactory extends AbstractFactory
{
    createFromData(data: object): DateBetweenFilter
    {
        let action = this.createNew();
        let object = <DateBetweenFilter>data;
        action.component = object.component;
        return action;
    }

    createNew(): DateBetweenFilter {
        return new DateBetweenFilter(this.application);
    }
}