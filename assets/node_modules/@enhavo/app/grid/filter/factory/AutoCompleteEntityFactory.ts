import AutoCompleteEntityFilter from "@enhavo/app/Grid/Filter/Model/AutoCompleteEntityFilter";
import AbstractFactory from "@enhavo/app/Grid/Filter/Factory/AbstractFactory";

export default class AutoCompleteEntityFactory extends AbstractFactory
{
    createFromData(data: object): AutoCompleteEntityFilter
    {
        let filter = this.createNew();
        let object = <AutoCompleteEntityFilter>data;
        filter.component = object.component;
        return filter;
    }

    createNew(): AutoCompleteEntityFilter {
        return new AutoCompleteEntityFilter();
    }
}
