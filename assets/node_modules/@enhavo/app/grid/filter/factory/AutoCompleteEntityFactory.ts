import AutoCompleteEntityFilter from "@enhavo/app/grid/filter/model/AutoCompleteEntityFilter";
import AbstractFactory from "@enhavo/app/grid/filter/factory/AbstractFactory";

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
