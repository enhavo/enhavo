import BetweenFilter from "@enhavo/app/Grid/Filter/Model/BetweenFilter";
import AbstractFactory from "@enhavo/app/Grid/Filter/Factory/AbstractFactory";

export default class BetweenFactory extends AbstractFactory
{
    createFromData(data: object): BetweenFilter
    {
        let filter = this.createNew();
        let object = <BetweenFilter>data;
        filter.component = object.component;
        return filter;
    }

    createNew(): BetweenFilter {
        return new BetweenFilter();
    }
}
