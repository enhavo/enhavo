import TextFilter from "@enhavo/app/Grid/Filter/Model/TextFilter";
import AbstractFactory from "@enhavo/app/Grid/Filter/Factory/AbstractFactory";

export default class TextFactory extends AbstractFactory
{
    createFromData(data: object): TextFilter
    {
        let action = this.createNew();
        let object = <TextFilter>data;
        action.component = object.component;
        return action;
    }

    createNew(): TextFilter {
        return new TextFilter(this.application)
    }
}