import AutoCompleteEntityFilter from "@enhavo/app/Grid/Filter/Model/AutoCompleteEntityFilter";
import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";

export default class AutoCompleteEntityFactory extends AbstractFactory
{
    createFromData(data: object): AutoCompleteEntityFilter
    {
        let action = this.createNew();
        let object = <AutoCompleteEntityFilter>data;
        action.component = object.component;
        return action;
    }

    createNew(): AutoCompleteEntityFilter {
        return new AutoCompleteEntityFilter(this.application)
    }
}
