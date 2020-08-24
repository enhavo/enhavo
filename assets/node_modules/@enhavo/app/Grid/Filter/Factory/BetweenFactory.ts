import BetweenFilter from "@enhavo/app/Grid/Filter/Model/BetweenFilter";
import AbstractFactory from "@enhavo/app/Grid/Filter/Factory/AbstractFactory";

export default class BetweenFactory extends AbstractFactory
{
    createFromData(data: object): BetweenFilter
    {
        let action = this.createNew();
        let object = <BetweenFilter>data;
        action.component = object.component;
        return action;
    }

    createNew(): BetweenFilter {
        return new BetweenFilter(this.application)
    }
}
