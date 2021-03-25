import TextFilter from "@enhavo/app/Grid/Filter/Model/TextFilter";
import AbstractFactory from "@enhavo/app/Grid/Filter/Factory/AbstractFactory";

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