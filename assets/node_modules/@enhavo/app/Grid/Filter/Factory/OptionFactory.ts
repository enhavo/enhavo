import OptionFilter from "@enhavo/app/Grid/Filter/Model/OptionFilter";
import AbstractFactory from "@enhavo/app/Grid/Filter/Factory/AbstractFactory";

export default class OptionFactory extends AbstractFactory
{
    createFromData(data: object): OptionFilter
    {
        let action = this.createNew();
        let object = <OptionFilter>data;
        action.component = object.component;
        return action;
    }

    createNew(): OptionFilter {
        return new OptionFilter(this.application)
    }
}