import BooleanFilter from "@enhavo/app/Grid/Filter/Model/BooleanFilter";
import AbstractFactory from "@enhavo/app/Grid/Filter/Factory/AbstractFactory";

export default class BooleanFactory extends AbstractFactory
{
    createFromData(data: object): BooleanFilter
    {
        let action = this.createNew();
        let object = <BooleanFilter>data;
        action.component = object.component;
        return action;
    }

    createNew(): BooleanFilter {
        return new BooleanFilter(this.application)
    }
}