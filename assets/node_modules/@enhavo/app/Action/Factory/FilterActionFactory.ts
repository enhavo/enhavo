import FilterAction from "@enhavo/app/Action/Model/FilterAction";
import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";

export default class FilterActionFactory extends AbstractFactory
{
    createFromData(data: object): FilterAction
    {
        let action = this.createNew();
        let object = <FilterAction>data;
        action.component = object.component;
        return action;
    }

    createNew(): FilterAction {
        return new FilterAction(this.application);
    }
}