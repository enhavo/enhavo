import DateBetweenFilter from "@enhavo/app/grid/filter/model/DateBetweenFilter";
import AbstractFactory from "@enhavo/app/grid/filter/factory/AbstractFactory";

export default class DateBetweenFactory extends AbstractFactory
{
    createNew(): DateBetweenFilter {
        return new DateBetweenFilter();
    }
}