import TextFilter from "@enhavo/app/grid/filter/model/TextFilter";
import AbstractFactory from "@enhavo/app/grid/filter/factory/AbstractFactory";

export default class TextFactory extends AbstractFactory
{
    createFromData(data: object): TextFilter
    {
        let filter = this.createNew();
        let object = <TextFilter>data;
        filter.component = object.component;
        return filter;
    }

    createNew(): TextFilter {
        return new TextFilter();
    }
}