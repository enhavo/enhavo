import BooleanFilter from "@enhavo/app/Grid/Filter/Model/BooleanFilter";
import AbstractFactory from "@enhavo/app/Grid/Filter/Factory/AbstractFactory";

export default class BooleanFactory extends AbstractFactory
{
    createFromData(data: object): BooleanFilter
    {
        let filter = this.createNew();
        let object = <BooleanFilter>data;
        filter.component = object.component;
        return filter;
    }

    createNew(): BooleanFilter {
        return new BooleanFilter();
    }
}